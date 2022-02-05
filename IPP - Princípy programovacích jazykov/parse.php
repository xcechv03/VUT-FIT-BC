<?php

ini_set('display_erors', 'stderr');
$xml = new DomDocument('1.0', 'UTF-8');
$xml -> formatOutput = true;

$header = false;
$rank = 0;

/**
 * @brief      findArgumentVar
 * Hľadá a kontroluje syntax premenných a vracia vstup ak je správny
 * @param      input  riadok, v ktorom hľadáme premenné
 *
 * @return     Returns vstup do funkcie ak je správny inak error
 */

function findArgumentVar($input)
{
    $symbol = '(^\s*LF\s*$|^\s*TF\s*$|^\s*GF\s*$)';
        if(strpos($input,"@"))
             $split = explode('@', $input);
        else exit(23);

        if(preg_match($symbol,$split[0])){
             if(preg_match('/[a-zA-Z0-9_\-$&%*!?]+/',$split[1]))
                 return $match = $input;
             else
                 exit(23);
        }else exit(22);

}

/**
 * @brief      findArgumentType
 * Hľadá a kontroluje syntax typu a vracia vstup ak je správny
 * @param      input  riadok, v ktorom hľadáme type
 *
 * @return     Returns vstup do funkcie ak je správny inak error
 */
function findArgumentType($input){

    $typeReg ='(^\s*string\s*$|^\s*bool\s*$|^\s*int\s*$)';
        if(preg_match($typeReg,$input))
              return $match = $input;

        else
             exit(23);
}

/**
 * @brief      findArgumentConstant
 * Hľadá a kontroluje syntax konštanty a vracia vstup ak je správny
 * @param      input  riadok, v ktorom hľadáme konštantu
 *
 * @return     Returns vstup do funkcie ak je správny inak error
 */

function findArgumentConstant($input){
    $symbol = '(^\s*LF\s*$|^\s*TF\s*$|^\s*GF\s*$)';
    $typeReg ='(^\s*string\s*$)';
    $typeint = '(^\s*int\s*$)';
    $typenil ='(^\s*nil\s*$)';
    $typebool ='(^\s*bool\s*$)';
    if(strpos($input,"@"))
        $split = explode('@', $input);
    else exit(23);

    if(preg_match($symbol,$split[0])){
        if(preg_match('/[a-zA-Z0-9_\-$&%*!?]+/',$split[1]))
            return $match = $input;
        else
             exit(23);
    }

    if(preg_match($typeReg,$split[0])){
        if(preg_match('/[a-zA-Z0-9_\-$&%*!?]+/',$split[1]))
            return $match = $input;
        elseif(preg_match('/\s*/',$split[1]))
            return $match = $input;
    else exit(23);
    }
    elseif(preg_match($typenil,$split[0])){
        if(preg_match($typenil,$split[1]))
            return $match = $input;
        else exit(23);
    }
    elseif(preg_match($typeint,$split[0])){
        if(preg_match('(^(\+|-)?[0-9]+\s*$)',$split[1]))
            return $match = $input;
        else exit(23);
    }
    elseif(preg_match($typebool,$split[0])){
        if(preg_match('(^\s*true\s*$|^\s*false\s*$)',$split[1]))
             return $match = $input;
    }else exit(23);
    exit(23);
}

/**
 * @brief      findArgumentLabel
 * Hľadá a kontroluje syntax labelu a vracia vstup ak je správny
 * @param      input  riadok, v ktorom hľadáme label
 *
 * @return     Returns vstup do funkcie ak je správny inak error
 */
function findArgumentLabel($input){
          if(preg_match('/[a-zA-Z0-9_\-$&%*!?]+/',$input))
              return $match = $input;
          else
              exit(23);
}

//================================================================================
// MAIN BODY
if ($argc > 1){
    if ($argv[1] == "--help"){

        echo"Use: skript číta zdrojový kód IPPcode19 zo STDIN, kontroluje lexikálnu a syntaktickú správnosť a vypíše XML na STDOUT   \n";
         exit(0);
    }else
exit(10);
}
// cyklus číta riadok za riadkom, kontroluje syntax a generuje XML
while($line = fgets(STDIN)) {
      if ($line[0] == '#') {                           // Ak je na line len komentár pokračuje na ďalší riadok
                continue;
      }
     if(preg_match("#^\s*$#",$line))            // Ak je line prázdna pokračuje na ďalší riadok
        continue;

      if (!$header){
          if(preg_match("#^[\s]*(.IPPcode21)[\s]*$#i",$line)){

              $header = true;
            $program = $xml ->createElement("program");
            $xml ->appendChild($program);
            $program -> setAttribute("language",'IPPcode21');
             continue;


          }else
              exit(21);
      }

      if(strpos($line, "#")){                   // Odstráni komentár
        $comment = explode("#", $line);
        $line = $comment[0];
      }

    $stringsymbol = '(LF|TF|GF)';

//rozdelí line podľa počtu medzier medzi výrazmi
    $splitted = explode(' ', trim(preg_replace('/\s+/', ' ', $line), " \n\t"));

        switch(strtoupper($splitted[0])){
//bez operatorov
            case 'CREATEFRAME':
            case 'PUSHFRAME'  :
            case 'POPFRAME':
            case 'RETURN':
            case 'BREAK':
                if(count($splitted) == 1){
                    $rank++;
            $instruction = $xml ->createElement("instruction");
            $program ->appendChild($instruction);
            $instruction ->setAttribute("order",$rank);
            $instruction ->setAttribute("opcode", strtoupper($splitted[0]));

                }else
                exit(23);
                break;
//1 operátor var
            case 'DEFVAR':
            case 'POPS':
                if(count($splitted) == 2){
                    $rank++;
                    $instruction = $xml ->createElement("instruction");
                    $program ->appendChild($instruction);
                    $instruction ->setAttribute("order",$rank);
                    $instruction ->setAttribute("opcode", strtoupper($splitted[0]));
                    $match = findArgumentVar($splitted[1]);
                    $arg1 = $xml ->createElement("arg1",$match);
                    $instruction ->appendChild($arg1);
                    $arg1 ->setAttribute("type","var");

                }else
                    exit(23);
                break;
// 1 operátor label
            case 'JUMP':
            case 'LABEL':
            case 'CALL':
                if(count($splitted) == 2){
                    $rank++;
                    $instruction = $xml ->createElement("instruction");
                    $program ->appendChild($instruction);
                    $instruction ->setAttribute("order",$rank);
                    $instruction ->setAttribute("opcode", strtoupper($splitted[0]));
                    $match = findArgumentLabel($splitted[1]);
                    $arg1 = $xml ->createElement("arg1",$match);
                    $instruction ->appendChild($arg1);
                    $arg1 ->setAttribute("type","label");
                 }else
                         exit(23);
             break;
//1 operátor symbol
            case 'PUSHS':
            case 'WRITE':
            case 'EXIT':
            case 'DPRINT':
            if(count($splitted) == 2){
                $rank++;
                $instruction = $xml ->createElement("instruction");
                $program ->appendChild($instruction);
                $instruction ->setAttribute("order",$rank);
                $instruction ->setAttribute("opcode", strtoupper($splitted[0]));
                $match = findArgumentConstant($splitted[1]);
                $matchsplit = explode('@', $match);
                if(preg_match($stringsymbol,$matchsplit[0]))
                {
                    $matchsplit[0]='var';
                    $matchsplit[1]=$match;
                }
                $arg1 = $xml ->createElement("arg1",$matchsplit[1]);
                $instruction ->appendChild($arg1);
                $arg1 ->setAttribute("type","$matchsplit[0]");

            }else
                exit(23);
            break;
//2 operátory var,symbol
            case 'MOVE':
            case 'TYPE':
            case 'INT2CHAR':
            case 'STRLEN':
            case 'NOT':
                if(count($splitted) == 3){
                    $rank++;
                    $instruction = $xml ->createElement("instruction");
                    $program ->appendChild($instruction);
                    $instruction ->setAttribute("order",$rank);
                    $instruction ->setAttribute("opcode", strtoupper($splitted[0]));
                    $match = findArgumentVar($splitted[1]);

                    $arg1 = $xml ->createElement("arg1",$match);
                    $instruction ->appendChild($arg1);
                    $arg1 ->setAttribute("type","var");

                    $match = findArgumentConstant($splitted[2]);
                    $matchsplit = explode('@', $match);
                    if(preg_match($stringsymbol,$matchsplit[0]))
                    {
                        $matchsplit[0]='var';
                        $matchsplit[1]=$match;
                    }
                    $arg2 = $xml ->createElement("arg2",$matchsplit[1]);
                    $instruction ->appendChild($arg2);
                    $arg2 ->setAttribute("type","$matchsplit[0]");

                }else
                    exit(23);
                break;
//2operátory var, type
            case 'READ':
                if(count($splitted) == 3){
                    $rank++;
                    $instruction = $xml ->createElement("instruction");
                    $program ->appendChild($instruction);
                    $instruction ->setAttribute("order",$rank);
                    $instruction ->setAttribute("opcode", strtoupper($splitted[0]));
                    $match = findArgumentVar($splitted[1]);

                    $arg1 = $xml ->createElement("arg1",$match);
                    $instruction ->appendChild($arg1);
                    $arg1 ->setAttribute("type","var");

                    $match = findArgumentType($splitted[2]);

                    $arg2 = $xml ->createElement("arg2",$match);
                    $instruction ->appendChild($arg2);
                    $arg2 ->setAttribute("type","type");
                }else
                    exit(23);
                break;
//3operátory var,symbol, symbol
        case 'ADD':
        case 'SUB':
        case 'MUL':
        case 'IDIV':
        case 'LT':
        case 'GT':
        case 'EQ':
        case 'AND':
        case 'OR':
        case 'STRI2INT':
        case 'CONCAT':
        case 'GETCHAR':
        case 'SETCHAR':
        if(count($splitted) == 4){
            $rank++;
            $instruction = $xml ->createElement("instruction");
            $program ->appendChild($instruction);
            $instruction ->setAttribute("order",$rank);
            $instruction ->setAttribute("opcode", strtoupper($splitted[0]));

            $match = findArgumentVar($splitted[1]);

            $arg1 = $xml ->createElement("arg1",$match);
            $instruction ->appendChild($arg1);
            $arg1 ->setAttribute("type","var");

            $match = findArgumentConstant($splitted[2]);
            $matchsplit = explode('@', $match);
            if(preg_match($stringsymbol,$matchsplit[0]))
            {
                $matchsplit[0]='var';
                $matchsplit[1]=$match;
            }
            $arg2 = $xml ->createElement("arg2",$matchsplit[1]);
            $instruction ->appendChild($arg2);
            $arg2 ->setAttribute("type","$matchsplit[0]");

            $match = findArgumentConstant($splitted[3]);
            $matchsplit = explode('@', $match);
            if(preg_match($stringsymbol,$matchsplit[0]))
            {
                $matchsplit[0]='var';
                $matchsplit[1]=$match;
            }
            $arg3 = $xml ->createElement("arg3",$matchsplit[1]);
            $instruction ->appendChild($arg3);
            $arg3 ->setAttribute("type","$matchsplit[0]");

        }else
            exit(23);
        break;

//3operátory label, symbol, symbol
        case 'JUMPIFEQ':
        case 'JUMPIFNEQ':
        if(count($splitted) == 4){
            $rank++;
            $instruction = $xml ->createElement("instruction");
            $program ->appendChild($instruction);
            $instruction ->setAttribute("order",$rank);
            $instruction ->setAttribute("opcode", strtoupper($splitted[0]));

            $match = findArgumentLabel($splitted[1]);
            $arg1 = $xml ->createElement("arg1",$match);
            $instruction ->appendChild($arg1);
            $arg1 ->setAttribute("type","label");

            $match = findArgumentConstant($splitted[2]);
            $matchsplit = explode('@', $match);
            if(preg_match($stringsymbol,$matchsplit[0]))
            {
                $matchsplit[0]='var';
                $matchsplit[1]=$match;
            }
            $arg2 = $xml ->createElement("arg2",$matchsplit[1]);

            $instruction ->appendChild($arg2);
            $arg2 ->setAttribute("type","$matchsplit[0]");

            $match = findArgumentConstant($splitted[3]);
            $matchsplit = explode('@', $match);
            if(preg_match($stringsymbol,$matchsplit[0]))
            {
                $matchsplit[0]='var';
                $matchsplit[1]=$match;
            }
            $arg3 = $xml ->createElement("arg3",$matchsplit[1]);

            $instruction ->appendChild($arg3);
            $arg3 ->setAttribute("type","$matchsplit[0]");
        }else
            exit(23);
        break;

            default: exit(22);

        }

}
  echo $xml->saveXML();

?>
