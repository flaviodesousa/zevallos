<?php
/**
 * zTagLibTransform
 *
 * Biblioteca de tranformação do conteúdo
 *
 * @package Library
 * @subpackage Transform
 * @version 1.0
 * @author Ruben Zevallos Jr. <zevallos@zevallos.com.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

/**
 * Inclui no processador de Tag a função Sentence
 *
 * <b>zPHP code:</b>
 * <code>
 * ztag_sentence($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->sentence() <-- Retorna as iniciais das letras maiúculas excluindo os "de, a" etc
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_Sentence($strThis, $strParam) {
  return sentence($strThis);
}

/**
 * Inclui no processador de Tag a função Substr
 *
 * <code>
 * ztag_substr($strThis, $strParam)
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_Substr($strThis, $strParam) {

  if ($strThis) return substr($strThis, $strParam);
}

/**
 * Formata a data recebida do sistema para data padrão BR
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_datebr($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $dateVar->datebr() <-- formata uma variável no formado de data DD/MM/AAAA
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_DateBR($strThis, $strParam) {
  return date("d/m/Y", strtotime($strThis));
}

/**
 * Formata a data e hora recebida do sistema para data padrão BR
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_datetimebr($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->datetimebr() <--
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_DateTimeBR($strThis, $strParam) {
  return date("d/m/Y H:i:s", strtotime($strThis));
}

/**
 * Formata a data recebida do sistema para data padrão US
 *
 * <code>
 * ztag_date($strThis, $strParam)
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_Date($strThis, $strParam) {
  return date("m/d/Y", strtotime($strThis));
}

/**
 * Formata a data e hora recebida do sistema para data padrão US
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_datetime($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->datetime() <--
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_DateTime($strThis, $strParam) {
  return date("m/d/Y H:i:s", strtotime($strThis));
}

/**
 * Retorna o HTML legível
 *
 * <code>
 * ztag_HTML($strThis, $strParam)
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_HTML($strThis, $strParam) {
  if ($strThis) return htmlentities($strThis);
}

/**
 * Retorna o tamanho da string
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_len($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->len() <-- retorna a quantidade de caracteres da string
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_Len($strThis, $strParam) {
	return strlen($strThis);
}

/**
 * Faz o crop da string e inclui ... no final se conseguir.
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_elipsis($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->elipsis(10) <-- Trunca o texto no 7o e inclui "..."
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_Elipsis($strThis, $strParam) {
	$intWidth = intval($strParam);

	$strThisOriginal = $strThis;

  if (strlen($strThis) > $intWidth) {
    $strThis = substr($strThis, 0, $intWidth);

    if (strlen($strThis) - 3 >= 6) $strThis = substr($strThis, 0, strlen($strThis) - 3)."...";

    $strThis = "<span title=\"$strThisOriginal\">$strThis</span>";
  }

	return $strThis;
}

/**
 * Retorna a string com os caracteres minúsculos
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_toLower($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->tolower() <-- Retorna todas as letras minúsculas
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_toLower($strThis, $strParam) {
  if (strlen($strThis)) return strtolower($strThis);
}

/**
 * Retorna a string com os caracteres maiúsculos
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_toUpper($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->toupper() <-- Retorna todas as letras maiúculas
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_toUpper($strThis, $strParam) {
  return strtoupper($strThis);
}

/**
 * Apaga os caracteres extras ao final da string
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_Trim($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->trim() <-- Apaga os espaços a direita da string
 * $stringVar->trim("\t") <-- Apaga os \t (tabs) a direita da string
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_Trim($strThis, $strParam) {
	if ($strParam) {
		return trim($strThis, $strParam);
	} else {
    return trim($strThis);
	}
}

/**
 * Apaga os caracteres extras a direita da string
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_RTrim($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->rtrim() <-- Apaga os espaços a direita da string
 * $stringVar->rtrim("\t") <-- Apaga os \t (tabs) a direita da string
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_RTrim($strThis, $strParam) {
  if ($strParam) {
    return rtrim($strThis, $strParam);
  } else {
    return rtrim($strThis);
  }
}

/**
 * Apaga os caracteres extras a esquerda da string
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_LTrim($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->ltrim() <-- Apaga os espaços a esquerda da string
 * $stringVar->ltrim("\t") <-- Apaga os \t (tabs) a esquerda da string
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_LTrim($strThis, $strParam) {
  if ($strParam) {
    return ltrim($strThis, $strParam);
  } else {
    return ltrim($strThis);
  }
}

/**
 * Apaga os caracteres extras a esquerda e direta da string
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_AllTrim($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->alltrim() <-- Apaga os espaços a direta e esquerda da string
 * $stringVar->alltrim("\t") <-- Apaga os \t (tabs) a direta e esquerda da string
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_AllTrim($strThis, $strParam) {
  if ($strParam) {
    return rtrim(ltrim($strThis, $strParam), $strParam);
  } else {
    return rtrim(ltrim($strThis));
  }
}

/**
 * Apaga as tags HTML e comandos PHP
 *
 * <code>
 * ztag_StripTags($strThis, $strParam)
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_StripTags($strThis, $strParam) {
  if ($strParam) {
    return strip_tags($strThis, $strParam);
  } else {
    return strip_tags($strThis);
  }
}

/**
 *  Adiciona barras invertidas a uma string
 *
 * <code>
 * ztag_AddSlashes($strThis)
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_AddSlashes($strThis, $strParam) {
	return addslashes($strThis);
}

/**
 * Retorna o resultado do IIF
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_IIF($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->iif($intVar > 10, "Then", "Else") <-- Executa o IIF retornando o resultado do THEN ou ELSE
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return boolean o resultado do empty
 *
 * @since Versão 1.0
 */
function ztag_IIF($varThis, $strParam) {
  return eval("return ztag_IIFFull(\$varThis, $strParam);");
}

/**
 * Executa o IIF para a ztag_IFF
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_IIFFull($varThis, $varCondition, $varThen, $varElse)
 * </code>
 *
 * @param variant $varThis conteúdo que será utilizado na expressão
 * @param variant $varCondition expressão com a condição que será avaliada
 * @param variant $varThen valor que será retornado no caso que a condição seja verdadeira
 * @param variant $varElse valor que será retornado no caso que a condição seja falsa
 *
 * @return variant o resultado do $varThen ou $varElse
 *
 * @since Versão 1.0
 */
function ztag_IIFFull($varThis, $varCondition, $varThen, $varElse) {

	if (eval("return $varCondition;")) {
		return $varThen;

	} else {
    return $varElse;
	}

}

/**
 * Retorna o resultado do Empty
 *
 * <code>
 * ztag_Empty($strThis, $strParam)
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return boolean o resultado do empty
 *
 * @since Versão 1.0
 */
function ztag_Empty($strThis, $strParam) {
  return empty($strThis);
}

/**
 * Retorna o resultado do Empty
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_CPF($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->formatcpf() <-- Retorna o texto no formato do CPF
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return boolean o resultado do empty
 *
 * @since Versão 1.0
 */
function ztag_FormatCPF($strThis, $strParam) {
  if ($strThis) {
    $strCPF = trim(ltrim($strThis, "-"));
    $strCPF = trim(ltrim($strCPF, "0"));

    $strCPF = str_pad($strCPF, 11, "0", STR_PAD_LEFT);

    return substr($strCPF, 0, 3).".".substr($strCPF, 3, 3).".".substr($strCPF, 6, 3)."-".substr($strCPF, 9, 2);
  }
}

/**
 * Retorna o número formatado
 *
 * <code>
 * ztag_FormatNumber($strThis, $strParam)
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return string o número formatado
 *
 * @since Versão 1.0
 */
function ztag_FormatNumber($varThis, $strParam) {

  if (strlen($varThis)) {
		switch (count(explode(",", $strParam))) {
	    case 0:
	      $strParam .= " 2, \",\", \".\"";
	      break;

	    case 1:
			  $strParam .= ", \",\", \".\"";
			  break;

	    case 2:
	      $strParam .= ", \".\"";
	      break;

		}

	  return eval("return number_format(\$varThis, $strParam);");
  }
}

/**
 * Retorna o número formatado no padrão BR
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_FormatNumberBR($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $numberVar->formatnumberbr() <-- Retorna o número formatado com 2 zeros a direita
 * $numberVar->formatnumberbr(0) <-- Retorna o número formatado, sem zeros a direita
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return string número formatado
 *
 * @since Versão 1.0
 */
function ztag_FormatNumberBR($strThis, $strParam) {
	$intCasas = 2;

	if (strlen($strParam)) $intCasas = intval($strParam);

  if ($strThis) {
	  if ($strThis) {
	    if (is_numeric($strThis)) {
	      $strThis = round(strval($strThis), $intCasas);

	      $strThis = strval(number_format($strThis, 2));

	    } else {
	      $strThis = str_replace(",", ".", $strThis);
	      $strThis = round(strval($strThis), $intCasas);

	    }

	    $strThis = ltrim($strThis, "0");

	    // echo "<br />dblValue=".$dblValue;

	    // "10,00" = "1000" / 100 = 10.00

	    if (strpos($strThis, $sFormatNumberBRDigit)) {
	      $strThis = str_replace(".", "", $strThis);
	      $strThis = str_replace(",", "", $strThis);

	      $strThis = (strval($strThis) / 100);

	    }
	  }

	  return number_format($strThis, $intCasas, ",", ".");

  }
}

/**
 * Deixa todas as letras iniciais maiúculas, menos os artigos
 *
 * <b>PHP code:</b>
 * <code>
 * ztag_Capitalize($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->capitalize() <-- Retorna os caracteres iniciais maiúsculos
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return string ajustada
 *
 * @since Versão 1.0
 */
function ztag_Capitalize($strThis, $strParam) {
  return sentence($strThis);

}

/**
 * Conta todos os caracteres com a opção de exluir os espaços
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_Count($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->count()
 * $stringVar->count(1) <-- Exclui os espaços da contagem
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return int quantidade de letras da string
 *
 * @since Versão 1.0
 */
function ztag_Count($strThis, $strParam) {
	$strPattern = "%[^ ]%sm";

	if ($strParam) $strPattern = "%.%sm";;

  preg_match_all($strPattern, $strThis, $Matches, PREG_OFFSET_CAPTURE);

  if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

  return count($Matches);
}

/**
 * Concatena o parâmetro à variável
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_Cat($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->cat("valor")
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return string retorna a variável com o conteúdo concatenado
 *
 * @since Versão 1.0
 */
function ztag_Cat($strThis, $strParam) {
  return $strThis.$strParam;
}

/**
 * Conta o número de parâgrafos
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_CountP($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->countp()
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return int retorna a quantidade de parágrafos
 *
 * @since Versão 1.0
 */
function ztag_CountP($strThis, $strParam) {
  $strPattern = "%^.*$%sm";

  preg_match_all($strPattern, $strThis, $Matches, PREG_OFFSET_CAPTURE);

  if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

  return count($Matches);
	}

/**
 * Conta o número de parâgrafos
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_CountParagraph($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->countparagraph()
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return int retorna a quantidade de parágrafos
 *
 * @since Versão 1.0
 */
	function ztag_CountParagraph($strThis, $strParam) {
  return ztag_CountP($strThis, $strParam);
}

/**
 * Conta o número de sentenças
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_CountS($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->countp()
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return int com o número de sentenças
 *
 * @since Versão 1.0
 */
function ztag_CountS($strThis, $strParam) {
  $strPattern = "%.*?[.!?]+%sm";

  preg_match_all($strPattern, $strThis, $Matches, PREG_OFFSET_CAPTURE);

  if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

  return count($Matches);
}

/**
 * Conta o número de sentenças
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_CountSentences($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->countsentences()
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return int com o número de sentenças
 *
 * @since Versão 1.0
 */
function ztag_CountSentences($strThis, $strParam) {
  return ztag_CountS($strThis, $strParam);
}

/**
 * Conta o número de palavras
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_CountW($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->countw()
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return int retorna com o número de palavras
 *
 * @since Versão 1.0
 */
function ztag_CountW($strThis, $strParam) {
  $strPattern = "%.*?[ .!?]+%sm";

  preg_match_all($strPattern, $strThis, $Matches, PREG_OFFSET_CAPTURE);

  if (preg_last_error()) debugError("<b>preg_last_error</b>:".preg_last_error());

  return count($Matches);
}

/**
 * Conta o número de palavras
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_CountWords($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * $stringVar->countwords()
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return int retorna com o número de palavras
 *
 * @since Versão 1.0
 */
function ztag_CountWords($strThis, $strParam) {
	return ztag_CountW($strThis, $strParam);
}

/**
 * Retorna a data de acordo com o formato.
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_DateFormat($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * dateVar->dateformat("d/m/Y")
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return string retorna com a data formatada
 *
 * @since Versão 1.0
 */
function ztag_DateFormat($strThis, $strParam) {
	return date($strParam, $strThis);
}

/**
 * Retorna a string com todos os caracteres escapados <, & e outros
 *
 * <b>PHP Code:</b>
 * <code>
 * ztag_Escape($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * dateVar->escape("d/m/Y")
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return string retorna com a string escapada
 *
 * @since Versão 1.0
 */
function ztag_Escape($strThis, $strParam) {
	return htmlentities($strThis);
}

// indent()(quantidade, "caracter) - indenta a "quantidade" de caracteres, sendo 4 o padrão e " " o padrão do caracter
function ztag_Indent($strThis, $strParam) {
	return "";
}
// lower() ou toLower - converte todas as letras para minúculas
function ztag_lower($strThis, $strParam) {
  return strtolower($strThis);
}

// upper() ou toUpper - converte todas as letras para minúsculas
function ztag_upper($strThis, $strParam) {
  return strtoupper($strThis);
	}

// nl2br() - converte todos os \r\n para <br />
function ztag_nl2br($strThis, $strParam) {
  return nl2br($strThis);
}

// nl2p() - converte todos os \r\n para <p>conteúdo da linha</p>
function ztag_nl2p($strThis, $strParam) {
  return "";
}

// nl2tag("tag") - converte todos os \r\n para a <tag>conteúdo da linha</tag> ou somente <tag>
function ztag_nl2tag($strThis, $strParam) {
  return "";
}

// regexReplace(expressão, resultado) - executa o replace usando regEx
function ztag_RegexReplace($strThis, $strParam) {
  return eval("return preg_replace($strParam, \$strThis);");
}

// replace(find, replace) - executa o replace
function ztag_Replace($strThis, $strParam) {
  return eval("return str_replace($strParam, \$strThis);");
	}

// spacify(int, caracter) - inclui espaços entre cada caracter da variável
function ztag_Spacify($strThis, $strParam) {
  return "";
}

// stringFormat("string no formato do printf") - formata a string com o formato do printf
function ztag_StringFormat($strThis, $strParam) {
  return eval("return printf(\$strThis, $strParam);");
}

// strip("caracter") troca \s+ \n+ \t+ pelo caracter definido
function ztag_Strip($strThis, $strParam) {
  return "";
}

// truncate(limit, "texto adicional", blnWord=0) - trunca os caracteres no limite e poderá incluir o texto adicional e/ou truncar na palavra.
function ztag_Truncate($strThis, $strParam) {
  return "";
}

// wordWrap(limit, "\n") - inclui no limite \n sempre no limite definido ou com a string definida
function ztag_WordWrap($strThis, $strParam) {
  return "";
}

// wordWrap(limit, "\n") - inclui no limite \n sempre no limite definido ou com a string definida
function ztag_Latin1($strThis, $strParam) {
  return accentString2Latin1($strThis, 1);
}

// wordWrap(limit, "\n") - inclui no limite \n sempre no limite definido ou com a string definida
function ztag_crlf2nl($strThis, $strParam) {
  return preg_replace("%\r\n%", "\\r\\n", $strThis);
}

// wordWrap(limit, "\n") - inclui no limite \n sempre no limite definido ou com a string definida
function ztag_iconv($strThis, $strParam) {
  return eval("return iconv($strParam, \$strThis);");
}

// wordWrap(limit, "\n") - inclui no limite \n sempre no limite definido ou com a string definida
function ztag_date2sql($strThis, $strParam) {

  if (strlen($strThis)) {
    preg_match_all("%(?P<all>(?P<first>[YMD])([YMD]+)?)%i", $strParam, $Matches, PREG_OFFSET_CAPTURE);

    foreach ($Matches[0] as $key => $value) {
      $strAll = $Matches["all"][$key][0];
      $intAll = strlen($strAll);

      $posAll = $Matches["all"][$key][1] - 1;

      $strPiece = substr($strThis, $posAll, $intAll);

      switch(strtolower($strAll)) {
        case "dd":
          $day = $strPiece;
          break;

        case "mm":
          $month = $strPiece;
          break;

        case "mmm":
          $month = $strPiece;
          break;

        case "yy":
        case "yyyy":
          $year = $strPiece;
          break;

        case "hh":
          $hour = $strPiece;
          break;

        case "nn":
          $minute = $strPiece;
          break;

        case "ss":
          $second = $strPiece;
          break;
      }
    }

    $strDateSQL = sprintf('%s/%s/%s %s:%s:%s', $year, $month, $day, $hour, $minute, $second);

    $strDateSQL = rtrim(rtrim(rtrim($strDateSQL, ':')), '/');

    return $strDateSQL;
  }
}
/**
 * Return current timestamp to SQL format YYYY/MM/DD HH:MM:SS
 *
 * <b>zPHP code:</b>
 * <code>
 * ztag_now($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * now2sql() <-- Return date time in SQL format
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_now($strThis, $strParam) {
  return date("Y/m/d H:i:s", time());
}

/**
 * Return current timestamp to SQL format YYYY-MM-DD HH:MM:SS
 *
 * <b>zPHP code:</b>
 * <code>
 * ztag_now2sql($strThis, $strParam)
 * </code>
 *
 * <b>zTag code:</b>
 * <code>
 * now2sql() <-- Return date time in SQL format
 * </code>
 *
 * @param string $strThis conteúdo que será transformado
 * @param string $strParam parâmetros prontos para o uso na função
 *
 * @return variant conteúdo tranformado
 *
 * @since Versão 1.0
 */
function ztag_now2sql($strThis, $strParam) {
  return date("Y/m/d H:i:s", time());
}

function ztag_dateHumanized($strThis, $strParam) {
	$dateFrom = strtotime($strThis);
	$dateTo   = time();
	
	$difference = DateDiff('s', $dateFrom, $dateTo, 1);
		
	$periodos = array('seg', 'min', 'hora', 'dia', 'semana', 'mês', 'ano');
	// arrTamanhos = array(60, 60, 24, 7, 4.35, 12, 10);
	$diff = array('s', 'n', 'h', 'd', 'w', 'm', 'yyyy');
	
	if ($difference > 0) {
	  $final = ' atrás';
	
	} else {
	  $difference = -$difference;
	  $final = ' por vir';
	
	}
	
	$j = 0;
	
	while ($difference > 0 && $j <= 7) {
	  $differenceLast = $difference;
	
	  $periodoLast = $periodo;
	
	  $periodo = $periodos[$j];
	
	  $difference = DateDiff($diff[$j], $dateFrom, $dateTo, 1);
	
	  $j++;
	
	}

	/*
	echo "<hr /><br />&nbsp;<br />DateDiff=".date("Y/m/d H:i:s", DateDiff('s', $dateFrom, $dateTo, 1));
	echo "<br />dateFrom=".date("Y/m/d H:i:s", $dateFrom);
	echo "<br />dateTo=".date("Y/m/d H:i:s", $dateTo);
		
	echo "<hr />periodo=$periodo";
	echo "<br />periodoLast=$periodoLast";
	echo "<br />difference=$difference";
	echo "<br />differenceLast=$differenceLast";
	echo "<br />dateFrom=$dateFrom";
	echo "<br />dateTo=$dateTo";
	*/
	
	if ($differenceLast != 1) $periodoLast .= 's';
	
	if ($periodoLast === 'mêss') $periodoLast = 'meses';
	
	return intval($differenceLast).' '.$periodoLast.$final;
	
}

function DateDiff($interval, $datefrom, $dateto, $using_timestamps = false) {
    /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
    */

    if (!$using_timestamps) {
        $datefrom = strtotime($datefrom, 0);
        $dateto = strtotime($dateto, 0);
    }
    $difference = $dateto - $datefrom; // Difference in seconds

    switch($interval) {

    case 'yyyy': // Number of full years
        $years_difference = floor($difference / 31536000);
        if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
            $years_difference--;
        }
        if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
            $years_difference++;
        }
        $datediff = $years_difference;
        break;

    case "q": // Number of full quarters

        $quarters_difference = floor($difference / 8035200);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $quarters_difference--;
        $datediff = $quarters_difference;
        break;

    case "m": // Number of full months

        $months_difference = floor($difference / 2678400);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $months_difference--;
        $datediff = $months_difference;
        break;

    case 'y': // Difference between day numbers

        $datediff = date("z", $dateto) - date("z", $datefrom);
        break;

    case "d": // Number of full days

        $datediff = floor($difference / 86400);
        break;

    case "w": // Number of full weekdays

        $days_difference = floor($difference / 86400);
        // $weeks_difference = floor($days_difference / 7); // Complete weeks

        if (abs($days_difference) >= 7) {
          $weeks_difference = floor($days_difference / 7);
        } else {
          $weeks_difference = 0;
        }

        $first_day = date("w", $datefrom);
        $days_remainder = floor($days_difference % 7);
        $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
        if ($odd_days > 7) { // Sunday
            $days_remainder--;
        }
        if ($odd_days > 6) { // Saturday
            $days_remainder--;
        }
        $datediff = ($weeks_difference * 5) + $days_remainder;
        break;

    case "ww": // Number of full weeks

        $datediff = floor($difference / 604800);
        break;

    case "h": // Number of full hours

        $datediff = floor($difference / 3600);
        break;

    case "n": // Number of full minutes

        $datediff = floor($difference / 60);
        break;

    default: // Number of full seconds (default)

        $datediff = $difference;
        break;
    }

    return $datediff;

}