<?php
/**
 * ownCloud - ocDownloader
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Xavier Beurois <www.sgc-univ.net>
 * @copyright Xavier Beurois 2015
 */

namespace OCA\ocDownloader\Controller\Lib;

class Tools
{
	public static function CheckURL ($URL)
	{
		$URLPattern = '%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu';
		
		preg_match ($URLPattern, $URL, $Matches);
		if (count ($Matches) == 1)
		{
			return true;
		}
		return false;
	}
	
	public static function CheckFilepath ($FP)
	{
		if (\OC\Files\Filesystem::file_exists ($FP))
        {
              return true;
        }
		return false;
	}
	
	public static function GetProgressString ($Completed, $Total)
	{
		$CompletedStr = self::FormatSizeUnits($Completed);
		$TotalStr = self::FormatSizeUnits($Total);
		
		return $CompletedStr . ' / ' . $TotalStr . ' (' . round((($Completed / $Total) * 100), 2) . '%)';
	}
	
	public static function FormatSizeUnits ($Bytes)
    {
        if ($Bytes >= 1073741824)
        {
            $Bytes = number_format ($Bytes / 1073741824, 2) . ' GB';
        }
        elseif ($Bytes >= 1048576)
        {
            $Bytes = number_format ($Bytes / 1048576, 2) . ' MB';
        }
        elseif ($Bytes >= 1024)
        {
            $Bytes = number_format ($Bytes / 1024, 2) . ' KB';
        }
        else
        {
            $Bytes = $Bytes . ' B';
        }

        return $Bytes;
	}
	
	public static function YouTubeDLInstalled ($YTBinary)
	{
		exec ('which ' . $YTBinary, $Output, $Return);
		
		if ($Return == 0)
		{
		    return true;
		}
		return false;
	}
	
	public static function CleanString ($Text)
	{
	    $UTF8 = Array
		(
	        '/[áàâãªä]/u'   =>   'a',
	        '/[ÁÀÂÃÄ]/u'    =>   'A',
	        '/[ÍÌÎÏ]/u'     =>   'I',
	        '/[íìîï]/u'     =>   'i',
	        '/[éèêë]/u'     =>   'e',
	        '/[ÉÈÊË]/u'     =>   'E',
	        '/[óòôõºö]/u'   =>   'o',
	        '/[ÓÒÔÕÖ]/u'    =>   'O',
	        '/[úùûü]/u'     =>   'u',
	        '/[ÚÙÛÜ]/u'     =>   'U',
	        '/ç/'           =>   'c',
	        '/Ç/'           =>   'C',
	        '/ñ/'           =>   'n',
	        '/Ñ/'           =>   'N',
	        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
	        '/[’‘‹›‚]/u'    =>   '', // Literally a single quote
	        '/[“”«»„]/u'    =>   '', // Double quote
	        '/ /'           =>   '_', // nonbreaking space (equiv. to 0x160)
	    );
    	return preg_replace (array_keys ($UTF8), array_values ($UTF8), $Text);
	}
}
?>