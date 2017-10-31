<?php 
            $dbhost='localhost';
            $dbuser='root';
            $dbpwd='stock12345';
            $store_folder = 'C:\DATABASE_BACKUP';
            $nowdir = "$store_folder\\".date("Ymd", time());
            if (file_exists($nowdir)) die("File exists.\n");
            @mkdir($nowdir);
            mysql_connect($dbhost,$dbuser,$dbpwd);
            $sql = mysql_list_dbs();
            while ($rs = mysql_fetch_array($sql))
            {
                $cmd = 'mysqldump --opt -u root --password=stock12345 --database '.$rs['Database'].'>'.$nowdir.'/'.$rs['Database'].'.sql';
                shell_exec($cmd);
                echo "dumping database $rs[Database].......\n";
            }
                echo "\nCompressing database......\n";
                $rar = "WinRAR\\WinRAR.exe a -ep1 -r -o+ -m5 -df \"$nowdir.rar\" \"$nowdir\" " ;
                shell_exec($rar);
                
                @unlink($store_folder.'/'.date("Ymd",time()-86400*5).'.rar');

            echo "\nOK!\n";
        ?>