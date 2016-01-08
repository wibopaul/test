<?
class IQLFeiertage extends IPSModule {
    public function Create() {
        //Never delete this line!
        parent::Create();
        //These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
        $this->RegisterPropertyString("country","D");
        $this->RegisterPropertyString("area", "NI");
        $this->RegisterPropertyString("areaAT", "W");
        $this->RegisterPropertyString("timeframe","today");
        $this->RegisterPropertyInteger("timerinterval",0);
        $this->RegisterTimer("UpdateTimer",0,"IQLFT_Update(\$_IPS['TARGET']);");
    }
    public function ApplyChanges() {
        //Never delete this line!
        parent::ApplyChanges();
        $this->RegisterVariableBoolean("IsHoliday", "Is Holiday");
        $this->RegisterVariableString("Holiday", "Feiertag");
        $this->SetTimerInterval("UpdateTimer",$this->ReadPropertyInteger("timerinterval")*60*60*1000);
        // Set Hidden
        IPS_SetHidden($this->GetIDForIdent("IsHoliday"),true);
        $this->Update();
    }
    private function GetFeiertag() {
        if($this->ReadPropertyString("timeframe") == "today") {
            $datum = date("Y-m-d",time());
        }
        if($this->ReadPropertyString("timeframe") == "tomorrow") {
            $tomorrow = strtotime("+1day");
            $datum = date("Y-m-d",$tomorrow);
        }
        if($this->ReadPropertyString("country") == "D") {
            $bundesland = $this->ReadPropertyString("area");
            $bundesland = strtoupper($bundesland);
            if (is_object($datum)) {
                $datum = date("Y-m-d", $datum);
            }
            $datum = explode("-", $datum);
            $datum[1] = str_pad($datum[1], 2, "0", STR_PAD_LEFT);
            $datum[2] = str_pad($datum[2], 2, "0", STR_PAD_LEFT);
            if (!checkdate($datum[1], $datum[2], $datum[0])) return false;
            $datum_arr = getdate(mktime(0,0,0,$datum[1],$datum[2],$datum[0]));
            $easter_d = date("d", easter_date($datum[0]));
            $easter_m = date("m", easter_date($datum[0]));
            $status = 'Arbeitstag';
            if ($datum_arr['wday'] == 0 || $datum_arr['wday'] == 6) $status = 'Wochenende';
            if ($datum[1].$datum[2] == '0101')
            {
                $status = 'Neujahr';
            }
            elseif ($datum[1].$datum[2] == '0106'
                && ($bundesland == 'BW' || $bundesland == 'BY' || $bundesland == 'ST'))
            {
                $status = 'Heilige Drei Könige';
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d-2,$datum[0])))
            {
                $status = 'Karfreitag';
            }
            elseif ($datum[1].$datum[2] == $easter_m.$easter_d)
            {
                $status = 'Ostersonntag';
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+1,$datum[0])))
            {
                $status = 'Ostermontag';
            }
            elseif ($datum[1].$datum[2] == '0501')
            {
                $status = 'Erster Mai';
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+39,$datum[0])))
            {
                $status = 'Christi Himmelfahrt';
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+49,$datum[0])))
            {
                $status = 'Pfingstsonntag';
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+50,$datum[0])))
            {
                $status = 'Pfingstmontag';
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+60,$datum[0]))
                && ($bundesland == 'BW' || $bundesland == 'BY' || $bundesland == 'HE' || $bundesland == 'NW' || $bundesland == 'RP' || $bundesland == 'SL' || $bundesland == 'SN' || $bundesland == 'TH'))
            {
                $status = 'Fronleichnam';
            }
            elseif ($datum[1].$datum[2] == '0815'
                && ($bundesland == 'SL' || $bundesland == 'BY'))
            {
                $status = 'Mariä Himmelfahrt';
            }
            elseif ($datum[1].$datum[2] == '1003')
            {
                $status = 'Tag der deutschen Einheit';
            }
            elseif ($datum[1].$datum[2] == '1031'
                && ($bundesland == 'BB' || $bundesland == 'MV' || $bundesland == 'SN' || $bundesland == 'ST' || $bundesland == 'TH'))
            {
                $status = 'Reformationstag';
            }
            elseif ($datum[1].$datum[2] == '1101'
                && ($bundesland == 'BW' || $bundesland == 'BY' || $bundesland == 'NW' || $bundesland == 'RP' || $bundesland == 'SL'))
            {
                $status = 'Allerheiligen';
            }
            elseif ($datum[1].$datum[2] == strtotime("-11 days", strtotime("1 sunday", mktime(0,0,0,11,26,$datum[0])))
                && $bundesland == 'SN')
            {
                $status = 'Buß- und Bettag';
            }
            elseif ($datum[1].$datum[2] == '1224')
            {
                $status = 'Heiliger Abend (Bankfeiertag)';
            }
            elseif ($datum[1].$datum[2] == '1225')
            {
                $status = '1. Weihnachtsfeiertag';
            }
            elseif ($datum[1].$datum[2] == '1226')
            {
                $status = '2. Weihnachtsfeiertag';
            }
            elseif ($datum[1].$datum[2] == '1231')
            {
                $status = 'Silvester (Bankfeiertag)';
            }
            return $status;
        }
        if($this->ReadPropertyString("country") == "AT") {
            $bundesland = $this->ReadPropertyString("areaAT");
            $bundesland = strtoupper($bundesland);
            if (is_object($datum))
            {
                $datum = date("Y-m-d", $datum);
            }
            $datum = explode("-", $datum); 
 
            $datum[1] = str_pad($datum[1], 2, "0", STR_PAD_LEFT); 
            $datum[2] = str_pad($datum[2], 2, "0", STR_PAD_LEFT); 
 
            if (!checkdate($datum[1], $datum[2], $datum[0])) return false; 
 
            $datum_arr = getdate(mktime(0,0,0,$datum[1],$datum[2],$datum[0])); 
 
            $easter_d = date("d", easter_date($datum[0])); 
            $easter_m = date("m", easter_date($datum[0])); 
 
            $status = 'Arbeitstag'; 
            if ($datum_arr['wday'] == 0 || $datum_arr['wday'] == 6) $status = 'Wochenende'; 
 
            if ($datum[1].$datum[2] == '0101')
            { 
                $status = 'Neujahr'; 
            }
            elseif ($datum[1].$datum[2] == '0106')
            { 
                $status = 'Heilige Drei Könige'; 
            }
            elseif ($datum[1].$datum[2] == '0319' && ($bundesland == 'K' || $bundesland == 'ST' || $bundesland == 'T' || $bundesland == 'V'))
            { 
                $status = 'Josef'; 
            } 
                elseif ($datum[1].$datum[2] == $easter_m.$easter_d)
            { 
                $status = 'Ostersonntag'; 
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+1,$datum[0])))
            { 
                $status = 'Ostermontag'; 
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+39,$datum[0])))
            { 
                $status = 'Christi Himmelfahrt'; 
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+49,$datum[0])))
            { 
                $status = 'Pfingstsonntag'; 
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+50,$datum[0])))
            { 
                $status = 'Pfingstmontag'; 
            }
            elseif ($datum[1].$datum[2] == date("md",mktime(0,0,0,$easter_m,$easter_d+60,$datum[0])))
            { 
                $status = 'Fronleichnam'; 
            }
            elseif ($datum[1].$datum[2] == '0501')
            { 
                $status = 'Erster Mai'; 
            }
            elseif ($datum[1].$datum[2] == '0504' && $bundesland == 'OOE')
            { 
                $status = 'Florian'; 
            }
            elseif ($datum[1].$datum[2] == '0815')
            { 
                $status = 'Mariä Himmelfahrt'; 
            }
            elseif ($datum[1].$datum[2] == '0924' && $bundesland == 'S')
            { 
                $status = 'Rupertitag'; 
            }
            elseif ($datum[1].$datum[2] == '1010' && $bundesland == 'K')
            { 
                $status = 'Tag der Volksabstimmung'; 
            }
            elseif ($datum[1].$datum[2] == '1026')
            { 
                $status = 'Nationalfeiertag'; 
            }
            elseif ($datum[1].$datum[2] == '1101')
            { 
                $status = 'Allerheiligen'; 
            }
            elseif ($datum[1].$datum[2] == '1111' && $bundesland == 'B')
            { 
                $status = 'Martini'; 
            }
            elseif ($datum[1].$datum[2] == '1115' && ($bundesland == 'NOE' || $bundesland == 'W'))
            { 
                $status = 'Leopoldi'; 
            }
            elseif ($datum[1].$datum[2] == '1208')
            { 
                $status = 'Mariä Empfängnis'; 
            }
            elseif ($datum[1].$datum[2] == '1224')
            { 
                $status = 'Heiliger Abend'; 
            }
            elseif ($datum[1].$datum[2] == '1225')
            { 
                $status = 'Christtag'; 
            }
            elseif ($datum[1].$datum[2] == '1226')
            { 
                $status = 'Stefanitag'; 
            }
            return $status; 
        }
    }
    public function Update() {
        $holiday = $this->GetFeiertag();
        SetValue($this->GetIDForIdent("Holiday"), $holiday);
        if($holiday != "Arbeitstag" and $holiday != "Wochenende") {
            SetValue($this->GetIDForIdent("IsHoliday"), true);
        }
        else {
            SetValue($this->GetIDForIdent("IsHoliday"), false);
        }
    }
}
