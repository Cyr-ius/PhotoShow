<?php
/**
 * This file implements the class LoginPage.
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright 2011 Thibaud Rohmer + 2013 Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
/**
 * LoginPage
 *
 * Lets a user log in.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @author    Psychedelys <psychedelys@gmail.com>
 * @copyright Thibaud Rohmer + Psychedelys
 * @license   http://www.gnu.org/licenses/
 * @oldlink   http://github.com/thibaud-rohmer/PhotoShow
 * @link      http://github.com/psychedelys/PhotoShow
 */
class LoginPage extends Page {
    /**
     * Create Login Page
     *
     * @author Thibaud Rohmer
     */
    public function __construct() {
    }
    /**
     * Display Login Page on website
     *
     * @return void
     * @author Thibaud Rohmer
     */
    public function toHTML() {
        if (Settings::$forcehttps && (!isset($_SERVER["HTTPS"]) || !$_SERVER["HTTPS"]) && (!isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) || ($_SERVER["HTTP_X_FORWARDED_PROTO"] != "https"))) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://" . Settings::$self_url . Settings::$self_path . "?t=Log"); # "$_SERVER["REQUEST_URI"]);
            exit();
        } else {
            $crypto_header = "<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/jsbn.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/jsbn2.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/prng4.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/rng.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/ec.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/sec.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/aes/aes.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/aes/aes-ctr.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/aes/base64.js\"></script>
<script type=\"text/javascript\" src=\"" . Settings::$self_path . "inc/js/aes/utf8.js\"></script>
<script type=\"text/javascript\">
/* <![CDATA[ */
var bob_priv = \"\";
var bob_pub_x = \"\";
var bob_pub_y = \"\";
var bob_key_x = \"\";
var bob_key_y = \"\";
var q = \"\";
var a = \"\";
var b = \"\";
var gx = \"\";
var gy = \"\";
var n = \"\";
var rng;

$(function(){
        $('input').keydown(function(e){
                if (e.keyCode == 13) {
                        do_login();
                        return false;
                }
        });
});

function set_ec_params(name) {
  var c = getSECCurveByName(name);

  q = c.getCurve().getQ().toString();
  a = c.getCurve().getA().toBigInteger().toString();
  b = c.getCurve().getB().toBigInteger().toString();
  gx = c.getG().getX().toBigInteger().toString();
  gy = c.getG().getY().toBigInteger().toString();
  n = c.getN().toString();

  // Changing EC params invalidates everything else
  bob_priv = \"\";
  bob_pub_x = \"\";
  bob_pub_y = \"\";
  bob_key_x = \"\";
  bob_key_y = \"\";
}

function get_curve() {
  return new ECCurveFp(new BigInteger(q),
    new BigInteger(a),
    new BigInteger(b));
}

function get_G(curve) {
  return new ECPointFp(curve,
    curve.fromBigInteger(new BigInteger(gx)),
    curve.fromBigInteger(new BigInteger(gy)));
}

function pick_rand() {
  var n1 = new BigInteger(n);
  var n2 = n1.subtract(BigInteger.ONE);
  var r = new BigInteger(n1.bitLength(), rng);
  return r.mod(n2).add(BigInteger.ONE);
}

function do_bob_rand() {
  var r = pick_rand();
  bob_priv = r.toString();
}

function do_bob_pub() {
  var curve = get_curve();
  var G = get_G(curve);
  var a1 = new BigInteger(bob_priv);
  var P = G.multiply(a1);
  bob_pub_x = P.getX().toBigInteger().toString();
  bob_pub_y = P.getY().toBigInteger().toString();
}

function do_bob_key() {
  var curve = get_curve();
  var formecdhtest = document.getElementById(\"form_ecdhtest\");
  var P = new ECPointFp(curve,
  curve.fromBigInteger(new BigInteger(formecdhtest.alice_pub_x.value)),
  curve.fromBigInteger(new BigInteger(formecdhtest.alice_pub_y.value)));
  var a1 = new BigInteger(bob_priv);
  var S = P.multiply(a1);
  bob_key_x = S.getX().toBigInteger().toString();
  bob_key_y = S.getY().toBigInteger().toString();
}

function do_init() {
  set_ec_params(\"secp192r1\");
  rng = new SecureRandom();
  do_bob_rand();
  do_bob_pub();
}

function do_login() {
  var formecdhtest = document.getElementById(\"form_ecdhtest\");
  if (formecdhtest.identifiant.value == \"\") {
     alert(\"Please enter your user name.\");
     return false;
  }

  if (formecdhtest.motdepasse.value == \"\") {
     alert(\"Please enter your password.\");
     return false;
  }
  do_bob_key();
  console.log('bobkey done:'+bob_key_x +','+ bob_key_y);
  console.log('bobpub:'+bob_pub_x+','+bob_pub_y);
  console.log('identifiant:'+formecdhtest.identifiant.value);
  console.log('motdepasse:'+formecdhtest.motdepasse.value);

  var formsubmit = document.getElementById(\"form_submit\");
  formsubmit.login.value = formecdhtest.identifiant.value;
  formsubmit.truc.value = Aes.Ctr.encrypt(formecdhtest.motdepasse.value, bob_key_x +','+ bob_key_y, 256);
  formsubmit.challenge.value = formecdhtest.challenge.value;
  formsubmit.bob_pub_x.value = bob_pub_x;
  formsubmit.bob_pub_y.value = bob_pub_y;
  console.log('method:'+formsubmit.getAttribute(\"method\"));
  formsubmit.setAttribute(\"method\", \"post\");
  console.log('login:'+formsubmit.login.value);
  console.log('truc:'+formsubmit.truc.value);
  console.log('chal:'+formsubmit.challenge.value);

  formsubmit.submit();
  }
/* ]]> */
</script>";
            $this->header($crypto_header);
            echo "<body><div class='center'>\n";
            echo "<form method='post' class='niceform' id='form_ecdhtest' action='' onsubmit='return false' >\n";
            echo "<div class='section'><h2>" . Settings::_("login", "logintitle") . "</h2>";
            /// Login
            echo "<fieldset>
                <div class='fieldname'>
                    <span>" . Settings::_("login", "login") . "</span>
                </div>
                <div class='fieldoptions'>
                    <input type='text' name='identifiant' id='identifiant'/>
                </div>
            </fieldset>\n";
            /// Password
            echo "<fieldset>
                <div class='fieldname'>
                    <span>" . Settings::_("login", "pass") . "</span>
                </div>
                <div class='fieldoptions'>
                    <input type='password' name='motdepasse' id='motdepasse'/>
                </div>
            </fieldset>\n";
            $g = NISTcurve::generator_192();
            $alice = new EcDH($g);
            $pubPoint = $alice->getPublicPoint();
            $pubPoint = str_replace('(', '', $pubPoint);
            $pubPoint = str_replace(')', '', $pubPoint);
            list($pubPoint_X, $pubPoint_Y) = explode(',', $pubPoint);
            //
            $_SESSION['alice_priv'] = $alice->getSecret();
            //print $alice->extractPubPoint();
            list($_SESSION['alice_curve_prime'], $_SESSION['alice_curve_a'], $_SESSION['alice_curve_b']) = explode(',', $alice->extractPubPoint());
            print "<input type='hidden' name='alice_pub_x' value='$pubPoint_X'/><br/>\n";
            print "<input type='hidden' name='alice_pub_y' value='$pubPoint_Y'/><br/>\n";
            print "<input type='hidden' name='challenge' id='challenge' value='" . Settings::$challenge . "'/><br/>\n";
            echo "<fieldset class='alignright'><input type='submit' value='" . Settings::_("login", "submit") . "' onclick=\"do_login();\" /> " . Settings::_("login", "or");
            if (!Settings::$noregister) {
                echo " <a class='inline' href='?t=Reg'>" . Settings::_("login", "register") . "</a> " . Settings::_("login", "or");
            }
            echo " <a class='inline' href='.'>" . Settings::_("login", "back") . "</a>";
            echo "</fieldset></div></form>\n";
            echo "<form method='post' action='" . Settings::$self_path . $_SERVER['PHP_SELF'] . "?t=Log' id='form_submit'>
  <div>
    <input type='hidden' name='login' id='login' />
    <input type='hidden' name='truc' id='truc' />
    <input type='hidden' name='bob_pub_x' id='bob_pub_x' />
    <input type='hidden' name='bob_pub_y' id='bob_pub_y' />
    <input type='hidden' name='challenge' id='challenge' />
  </div>
</form>";
            echo "</div>\n";
            echo "<script  type=\"text/javascript\" >
window.onload=do_init;
</script></body></html>";
        }
    }
}
?>
