<?php
class EmailMessage implements HTMLObject {

	protected $connection;
	protected $messageNumber;
	
	public $subject = '';
	public $bodyHTML = '';
	public $bodyPlain = '';
	public $attachments;
	
	public $getAttachments = true;
	
	public function __construct($connection, $messageNumber) {
	
		$this->connection = $connection;
		$this->messageNumber = $messageNumber;
		
	}

	public function fetch() {
		$header = @imap_headerinfo($this->connection, $this->messageNumber);
		$this->subject = $header->subject;
		$structure = @imap_fetchstructure($this->connection, $this->messageNumber);
		if(!$structure) {
			return false;
		}
		else {
			$this->recurse($structure->parts);
			return true;
		}
		
	}
	
	public function recurse($messageParts, $prefix = '', $index = 1, $fullPrefix = true) {

		foreach($messageParts as $part) {
			
			$partNumber = $prefix . $index;
			
			if($part->type == 0) {
				if($part->subtype == 'PLAIN') {
					$this->bodyPlain .= $this->getPart($partNumber, $part->encoding);
				}
				else {
					$this->bodyHTML .= $this->getPart($partNumber, $part->encoding);
				}
			}
			elseif($part->type == 2) {
				$msg = new EmailMessage($this->connection, $this->messageNumber);
				$msg->getAttachments = $this->getAttachments;
				$msg->recurse($part->parts, $partNumber.'.', 0, false);
				$this->attachments[] = array(
					'type' => $part->type,
					'subtype' => $part->subtype,
					'filename' => '',
					'data' => $msg,
					'inline' => false,
				);
			}
			elseif(isset($part->parts)) {
				if($fullPrefix) {
					$this->recurse($part->parts, $prefix.$index.'.');
				}
				else {
					$this->recurse($part->parts, $prefix);
				}
			}
			elseif($part->type > 2) {
				if(isset($part->id)) {
					$id = str_replace(array('<', '>'), '', $part->id);
					$this->attachments[$id] = array(
						'type' => $part->type,
						'subtype' => $part->subtype,
						'filename' => $this->getFilenameFromPart($part),
						'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
						'inline' => true,
					);
				}
				else {
					$this->attachments[] = array(
						'type' => $part->type,
						'subtype' => $part->subtype,
						'filename' => $this->getFilenameFromPart($part),
						'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
						'inline' => false,
					);
				}
			}
			
			$index++;
			
		}
		
	}
	
	function getPart($partNumber, $encoding) {

		$data = imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
		switch($encoding) {
			case 0: return $data; // 7BIT
			case 1: return $data; // 8BIT
			case 2: return $data; // BINARY
			case 3: return base64_decode($data); // BASE64
			case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
			case 5: return $data; // OTHER
		}


	}
	
	function getFilenameFromPart($part) {

		$filename = '';

		if($part->ifdparameters) {
			foreach($part->dparameters as $object) {
				if(strtolower($object->attribute) == 'filename') {
					$filename = $object->value;
				}
			}
		}

		if(!$filename && $part->ifparameters) {
			foreach($part->parameters as $object) {
				if(strtolower($object->attribute) == 'name') {
					$filename = $object->value;
				}
			}
		}

		return $filename;

	}
	
	public function Check($import=null){
 		if( !(CurrentUser::$admin || CurrentUser::$uploader) ){
			throw new jsonRPCException('Insufficients rights for Upload');
 		}

		if (!Settings::$mailreceiver_enable) {
			throw new jsonRPCException("Mail Receiver disabled.");
		}
		
		if (Settings::$mailreceiver_port=='993'){
			$ssl = '/ssl';
		}
		if (Settings::$mailreceiver_port=='110'){
			$ssl = '/pop3';
		}		
		if (!Settings::$mailreceiver_cert) {
			$cer = '/novalidate-cert';
		}
		
		$server = '{'.Settings::$mailreceiver_server.':'.Settings::$mailreceiver_port.$ssl.$cer.'}INBOX';
		$login = Settings::$mailreceiver_username;
		$password =Settings::$mailreceiver_password;

		$connection = imap_open($server, $login, $password);
		$number = imap_num_msg($connection);

		if ($import) {
			for ($i=1; $i<=$number; $i++) {
				$emailMessage = new EmailMessage($connection, $i);
				$emailMessage->getAttachments = true;
				$emailMessage->fetch();
				$att_path = File::r2a($emailMessage->subject);
				if (!file_exists($att_path)) {
					imap_delete($connection,$i);
					continue;
				}
				foreach ($emailMessage->attachments as $item) {
					$fs = File::unikname($att_path.'/'.$item['filename']);
					$fp = fopen($fs, 'w');
					$data = $item['data'];
					fputs($fp, $data);
					fclose($fp);
					$j++;
				}
				imap_delete($connection,$i);
			}
			imap_expunge($connection);
		}
		imap_close($connection);
	return array('mail'=>$number,'attachments'=>$j);
	}
	
	function toHTML() {
	}
}