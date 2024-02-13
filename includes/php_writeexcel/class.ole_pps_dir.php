<?php

/*
 * Copyleft 2002 Johann Hanne
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either

 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this software; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA  02111-1307 USA
 */

/*
 * This is the OLE::Storage_Lite Perl package ported to PHP
 * OLE::Storage_Lite was written by Kawai Takanori, kwitknr@cpan.org
 */

require_once "class.ole_pps.php";
require_once "functions.ole.php";

class ole_pps_dir extends ole_pps {

    function ole_pps_dir($sName, $raTime1st=false, $raTime2nd=false,
                     $raChild=false) {
        $this->No         = false;
        $this->Name       = $sName;
        $this->Type       = PpsType_Dir;
        $this->PrevPps    = false;
        $this->NextPps    = false;
        $this->DirPps     = false;
        $this->Time1st    = $raTime1st;
        $this->Time2nd    = $raTime2nd;
        $this->StartBlock = false;
        $this->Size       = false;
        $this->Data       = false;
        $this->Child      = $raChild;
    }

}

?>
