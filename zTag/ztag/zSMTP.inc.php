<?php
/**
 * zSMTP
 *
 * Processa as tags zSMTP
 *
 * @package ztag
 * @subpackage template
 * @category help
 * @version $Revision$
 * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link http://ztag.zyc.com.br
 * @copyright 2007-2010 by Ruben Zevallos(r) Jr.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://ztag.zyc.com.br> *
 */

/*
<zsmtp:new id="smtpD2" host="alfa.d2.net.br" user="user" password="password" port="25" />

<zsmtp:message id="bodyText">
This is a TXT body
</zsmtp:message>

<zsmtp:message id="bodyHTML">
This is a <strong>HTML</strong> body
</zsmtp:message>

<zsmtp:send use="smtpD2" textbody="bodyText" htmlbody="bodyHTML" from="ze@d2.net.br" to="teste@gmail.com" subject="This is the Subject" />

 */
define("zsmtpVersion", 1.0, 1);
define("zsmtpVersionSufix", "ALFA 0.1", 1);

/**
 * Just to check if zTag was loaded
 *
 * <code>
 * zsmtp_exist();
 * </code>
 *
 * @return boolean 1 if exist
 *
 * @since 1.0
 */
function zsmtp_exist() {
  return 1;
}

/**
 * Return this zTag version
 *
 * <code>
 * zsmtp_version();
 * </code>
 *
 * @return string with zTag version
 *
 * @since 1.0
 */
function zsmtp_version() {
  return zsmtpVersion." ".zsmtpVersionSufix;
}

/**
 * Compare the version parameter with current version
 *
 * <code>
 * zsmtp_compare();
 * </code>
 *
 * @param number $version the version to compare
 *
 * @return boolean true if match with current version
 *
 * @since 1.0
 */
function zsmtp_compare($version) {
  return zsmtpVersion === $version;
}

/**
 * Main zTag functions selector
 *
 * <code>
 * zsmtp_execute($tagId, $tagFunction, $arrayTag, $arrayTagId, $arrayOrder);
 * </code>
 *
 * @param integer $tagId array id of current zTag of $arrayTag array
 * @param string $tagFunction name of zTag function
 * @param array $arrayTag array with all compiled zTags
 * @param array $arrayTagId array with all Ids values
 * @param array $arrayOrder array with zTag executing order
 *
 * @since 1.0
 */
function zsmtp_zexecute($tagId, $tagFunction, &$arrayTag, &$arrayTagId, $arrayOrder) {
  $arrParam = $arrayTag[$tagId][ztagParam];

  $strValue     = $arrParam["value"];
  $strVar       = $arrParam["var"];
  $strUse       = $arrParam["use"];
  $strTransform = $arrParam["transform"];

  $stmpError = array(211 => "System status, or system help reply"
                   , 214 => "Help message"
                   , 220 => "Service ready"
                   , 221 => "Service closing transmission channel"
                   , 250 => "Requested mail action okay, completed"
                   , 251 => "User not local; will forward to"
                   , 354 => "Start mail input; end with ."
                   , 421 => "Service not available, closing transmission channel"
                   , 450 => "Requested mail action not taken: mailbox unavailable"
                   , 451 => "Requested action aborted: local error in processing"
                   , 452 => "Requested action not taken: insufficient system storage"
                   , 500 => "Syntax error, command unrecognized"
                   , 501 => "Syntax error in parameters or arguments"
                   , 502 => "Command not implemented"
                   , 504 => "Command parameter not implemented"
                   , 550 => "Requested action not taken: mailbox unavailable"
                   , 551 => "User not local; please try"
                   , 552 => "Requested mail action aborted: exceeded storage allocation"
                   , 553 => "Requested action not taken: mailbox name not allowed"
                   , 554 => "Transaction failed");

  if ($arrayTag[$tagId][ztagContentWidth]) $strContent = ztagVars($arrayTag[$tagId][ztagContent], $arrayTagId);

  $errorMessage = "";

  switch (strtolower($tagFunction)) {
  	/* Documentation
  	 * \/\*\+.*?\*\s+(?P<ztag><.*?>).*?\*((?P<param>.*?\*\s+.*?).*?\*\/)
  	 *
  	 * Parameters
  	 * \*\s+(?P<param>\w+)\s*=\s*["'](?P<option>.*?)['"]([ ]+(?P<description>.*))?
  	 */

   /*+
     * New
     *
     * <code>
     * <zsmtp:new id="smtpD2" host="alfa.d2.net.br" user="user" password="password" port="25" />
     * </code>
     *
     * @param string id="smtpD2"
     * @param string host="alfa.d2.net.br" SMTP Server Address.
     * @param string user="user" SMTP Username.
     * @param string password="password" SMTP Password.
     * @param string port="25" SMTP Port.
     * @param string timeout="5" SMTP Timeout (in seconds).
     *
     * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
     */
    case "new":
      $errorMessage .= ztagParamCheck($arrParam, "id,host");

      if ($strId) {
			  $arrayTagId[$strId][ztagIdValue]  = $arrParam;

			  $arrayTagId[$strId][ztagIdType] = idTypeSMTP;

			}
    	break;

			// <zsmtp:message id="bodyHTML" var="bodyHTML">
      case "message":
      $errorMessage .= ztagParamCheck($arrParam, "id");

      if ($strTransform) $strContent = ztagTransform($strContent, $strTransform);

      if ($strId) {
        $arrayTagId[$strId][ztagIdValue]  = $strContent;
        $arrayTagId[$strId][ztagIdLength] = strlen($strContent);

        $arrayTagId[$strId][ztagIdType] = idTypeMessage;

      }

      if ($strVar) {
        $arrayTagId["$".$strVar][ztagIdValue] = $strContent;
        $arrayTagId["$".$strVar][ztagIdType]  = idTypeMessage;
      }

      break;

    /*+
     * Send
     *
     * </code>
     * <zsmtp:send use="smtpD2" body="bodyText" from="ze@d2.net.br" to="teste@gmail.com" subject="This is the Subject" />
     * </code>
     *
     * @param string Parameter=Value/Option Description
     * @param string use="smtpD2"
     * @param string body="bodyText"
     * @param string textbody="bodyText"
     * @param string htmlbody="bodyHTML"
     * @param string from="ze@d2.net.br"
     * @param string to="teste@gmail.com"
     * @param string cc="testecc@gmail.com"
     * @param string bcc="testebcc@gmail.com"
     * @param string replyto="no-reply@mydomain.com"
     * @param string mailed-by="zTag zSMTP"
     * @param string errorsto="error@avolio.com"
     * @param string sender=admin@avolio.com"
     * @param string inreplyto="4.3.2.7.2.20010415150149.00b08080@mail.example.com"
     * @param string Date="Thu, Nov 18, 2010 at 5:40 PM"
     * @param string mailtype="{text}|html" Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
     * @param string contenttype="text/plain[|text/html]"
     * @param string charset="{ISO-8859-1}|UTF-8" Character set (utf-8, iso-8859-1, etc.).
     * @param string mimeversion="1.0"
     * @param string priority="1|2|{3}|4|5" Email Priority - 1 = highest, 5 = lowest, 3 = normal.
     * @param string xmailer="zTag zSMTP"
     * @param string subject="This is the Subject"
     * @param string wordwrap="1|{0}"
     * @param string wrapchars="{76}"
     */
    case "send":
    	$errorMessage .= ztagParamCheck($arrParam, "use,from,to,subject");

      $strUse     = $arrParam["use"];
      $strBody    = $arrParam["textbody"];
      $strHTML    = $arrParam["htmlbody"];
      $strFrom    = $arrParam["from"];
      $strReplyTo = $arrParam["replyto"];
      $strTo      = $arrParam["to"];
      $strSubject = $arrParam["subject"];

      if ($strId) $arrServer = $arrayTagId[$strId][ztagIdValue];

      $strHost     = $arrServer["host"];
      $strUser     = $arrServer["user"];
      $strPassword = $arrServer["password"];
      $strPort     = $arrServer["port"];

      if (!$strPort) $strPort = "25";

			if ($stmpSocket = fsockopen ($strHost, $strPort)) {
				fputs ($stmpSocket, "EHLO ".$HTTP_HOST."\r\n");
				$stmp["hello"] = fgets($stmpSocket, 1024 );

				if ($strUser && $strPassword) {
					fputs($stmpSocket, "AUTH LOGIN\r\n");
					$stmp["res"] = fgets($stmpSocket, 1024);

					// 334 <-- Ok
					//  $reply_code = (int)substr($this->get_response(), 0, 3);

					fputs($stmpSocket, base64_encode($strUser)."\r\n");
					$stmp["user"] = fgets($stmpSocket, 1024);
          // 334 <-- Ok

					fputs($stmpSocket, base64_encode($strPassword)."\r\n");
					$stmp["pass"] = fgets($stmpSocket, 256);
				  // 235 <-- Ok
				}

				fputs ($stmpSocket, "MAIL FROM: <".$strFrom.">\r\n");
				$stmp["From"] = fgets($stmpSocket, 1024 );
				// 250 <-- Ok
				// htmlspecialchars($from)

				if ($strReplyTo) {
	        fputs ($stmpSocket, "Reply-To: <".$strReplyTo.">\r\n");
	        $stmp["ReplyTo"] = fgets($stmpSocket, 1024 );
        }

        /*
	      $boundary = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_abcdefghijklmnopqrstuvwxyz'), 0, 32);
	      $boundary_header = "\r\nContent-Type: multipart/alternative; boundary=\"$boundary\"";

	      $body = "--{$boundary}\r\nContent-Type: text/plain; charset={$this->charset}\r\n\r\n$alt_message\r\n\r\n";

	      $body .= "\r\n--{$boundary}\r\nContent-Type: text/html; charset={$this->charset}\r\n\r\n$message\r\n\r\n";

	      $body .= "--{$boundary}--\r\n.\r\n";
        */


        // RCPT TO :anotherdude@target.local notify=success,failure
				fputs ($stmpSocket, "RCPT TO: <".$strTo.">\r\n");
				$stmp["To"] = fgets($stmpSocket, 1024);
        // 250 <-- Ok

				fputs($$stmpSocket, "DATA\r\n");
				$stmp["data"] = fgets($stmpSocket, 1024 );
        // 354 <-- Ok

				// Date: Thu, 21 May 2008 05:33:29 -0700
				// From: SamLogic <mail@samlogic.com>
				// Subject: The Next Meeting
				// To: john@mail.com
				// Errors-to: <error@avolio.com>
				// Sender: <admin@avolio.com>
				// In-Reply-To: <4.3.2.7.2.20010415150149.00b08080@mail.example.com>

				// $strXMailer
				$strData = "To: <".$strTo.">"
                          ."\r\nFrom: <".$strFrom.">";

        $strData .= "\r\nSubject:".$strSubject;

        $strData .= "\r\n\r\n\r\n".$strBody

        fputs($stmpSocket,
				                  ."\r\nDate:".date('r')
				                  ."\r\nContent-Type:".empty($strHTML) ? 'text/html' : 'text/plain'). '; charset='. $strCharSet
				                  ."\r\nMime-Version:1.0"
				                  ."\r\nPriority:normal"
				                  ."\r\nX-Mailer:zTag zSMTP"
                          ."\r\n.\r\n");

				$stmp["send"] = fgets($stmpSocket, 256);
        // 354 <-- Ok

				fputs ($stmpSocket, "QUIT\r\n");
				fclose($stmpSocket);

				/*
				 * http://www.tcpipguide.com/free/t_MIMEContentTypeHeaderandDiscreteMediaTypesSubtypes-3.htm
				 * Content-Type: <type>/<subtype> [; parameter1 ; parameter2 .. ; parameterN ]
				 * Content-type: text/plain; charset=“us-ascii”
				 * Content-type: image/jpeg; name=“ryanpicture.jpg”
				 * http://www.iana.org/assignments/media-types/index.html
				 *
				 */

				echo "<pre>".print_r($stmp, 1)."</pre>";
			}
      break;

    default:
      $errorMessage .= "<br />Undefined function \"$tagFunction\"";

  }

  ztagError($errorMessage, $arrayTag, $tagId);
}
