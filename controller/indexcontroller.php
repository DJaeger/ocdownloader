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

namespace OCA\ocDownloader\Controller;

use \OCP\IRequest;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\AppFramework\Controller;
use \OCP\Config;

use \OCA\ocDownloader\Controller\Lib\Tools;
use \OCA\ocDownloader\Controller\Lib\Settings;

class IndexController extends Controller
{
      private $DbType = 0;
      private $CurrentUID = null;
      
      public function __construct ($AppName, IRequest $Request, $CurrentUID)
      {
            parent::__construct($AppName, $Request);
            $this->CurrentUID = $CurrentUID;
            
            if (strcmp (Config::getSystemValue ('dbtype'), 'pgsql') == 0)
            {
                  $this->DbType = 1;
            }
      }

      /**
       * @NoAdminRequired
       * @NoCSRFRequired
       */
      public function add ()
      {
            $Settings = new Settings ('personal');
            $Settings->SetUID ($this->CurrentUID);
            
            $Settings->SetKey ('TorrentsFolder');
            $TorrentsFolder = $Settings->GetValue ();
            
            $SQL = 'SELECT * FROM `*PREFIX*ocdownloader_queue` WHERE (STATUS != ? OR STATUS IS NULL) AND IS_DELETED = ? ORDER BY TIMESTAMP DESC';
            if ($this->DbType == 1)
            {
                  $SQL = 'SELECT * FROM *PREFIX*ocdownloader_queue WHERE ("STATUS" != ? OR "STATUS" IS NULL) AND "IS_DELETED" = ? ORDER BY "TIMESTAMP" DESC';
            }
            
            $Query = \OCP\DB::prepare ($SQL);
            $Result = $Query->execute (Array (4, 0));
            
            return new TemplateResponse ('ocdownloader', 'add', [ 
                  'PAGE' => 0, 
                  'NBELT' => $Query->rowCount (), 
                  'QUEUE' => $Result,
                  'TTSFOLD' => $TorrentsFolder
            ]);
      }
      
      /**
       * @NoAdminRequired
       * @NoCSRFRequired
       */
      public function actives ()
      {
            $SQL = 'SELECT * FROM `*PREFIX*ocdownloader_queue` WHERE STATUS = ? AND IS_DELETED = ? ORDER BY TIMESTAMP DESC';
            if ($this->DbType == 1)
            {
                  $SQL = 'SELECT * FROM *PREFIX*ocdownloader_queue WHERE "STATUS" = ? AND "IS_DELETED" = ? ORDER BY "TIMESTAMP" DESC';
            }
            
            $Query = \OCP\DB::prepare ($SQL);
            $Result = $Query->execute (Array (1, 0));
            
            return new TemplateResponse('ocdownloader', 'actives', [
                  'PAGE' => 1,
                  'NBELT' => $Query->rowCount (),
                  'QUEUE' => $Result
            ]);
      }
      
      /**
       * @NoAdminRequired
       * @NoCSRFRequired
       */
      public function waitings ()
      {
            $SQL = 'SELECT * FROM `*PREFIX*ocdownloader_queue` WHERE STATUS = ? AND IS_DELETED = ? ORDER BY TIMESTAMP DESC';
            if ($this->DbType == 1)
            {
                  $SQL = 'SELECT * FROM *PREFIX*ocdownloader_queue WHERE "STATUS" = ? AND "IS_DELETED" = ? ORDER BY "TIMESTAMP" DESC';
            }
            
            $Query = \OCP\DB::prepare ($SQL);
            $Result = $Query->execute (Array (2, 0));
            
            return new TemplateResponse('ocdownloader', 'waitings', [
                  'PAGE' => 2,
                  'NBELT' => $Query->rowCount (),
                  'QUEUE' => $Result
            ]);
      }
      
      /**
       * @NoAdminRequired
       * @NoCSRFRequired
       */
      public function stopped ()
      {
            $SQL = 'SELECT * FROM `*PREFIX*ocdownloader_queue` WHERE STATUS = ? AND IS_DELETED = ? ORDER BY TIMESTAMP DESC';
            if ($this->DbType == 1)
            {
                  $SQL = 'SELECT * FROM *PREFIX*ocdownloader_queue WHERE "STATUS" = ? AND "IS_DELETED" = ? ORDER BY "TIMESTAMP" DESC';
            }
            
            $Query = \OCP\DB::prepare ($SQL);
            $Result = $Query->execute (Array (3, 0));
            
            return new TemplateResponse('ocdownloader', 'stopped', [
                  'PAGE' => 3,
                  'NBELT' => $Query->rowCount (),
                  'QUEUE' => $Result
            ]);
      }
      
      /**
       * @NoAdminRequired
       * @NoCSRFRequired
       */
      public function removed ()
      {
            $SQL = 'SELECT * FROM `*PREFIX*ocdownloader_queue` WHERE STATUS = ? AND IS_DELETED = ? ORDER BY TIMESTAMP DESC';
            if ($this->DbType == 1)
            {
                  $SQL = 'SELECT * FROM *PREFIX*ocdownloader_queue WHERE "STATUS" = ? AND "IS_DELETED" = ? ORDER BY "TIMESTAMP" DESC';
            }
            
            $Query = \OCP\DB::prepare ($SQL);
            $Result = $Query->execute (Array (4, 0));
            
            return new TemplateResponse('ocdownloader', 'removed', [
                  'PAGE' => 4,
                  'NBELT' => $Query->rowCount (),
                  'QUEUE' => $Result
            ]);
      }
}