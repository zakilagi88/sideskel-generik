<?php

namespace App\Services;

use Illuminate\Database\Console\DumpCommand;

class StatusAnakService
{

    public function getBbUIndeks($beratBadan, $umurBulan, $jenisKelamin)
    {
        $bbuData = json_decode(file_get_contents(resource_path('json/bb_u.json')), true)['bb_u'];
        $umurRef = self::getUmurRef($bbuData, $umurBulan, $jenisKelamin);
        return self::calculateIndeks($beratBadan, $umurRef);
    }

    public function getTbUIndeks($tinggiBadan, $umurBulan, $jenisKelamin)
    {
        $tbuData = json_decode(file_get_contents(resource_path('json/tb_u.json')), true)['tb_u'];
        $umurRef = self::getUmurRef($tbuData, $umurBulan, $jenisKelamin);
        return self::calculateIndeks($tinggiBadan, $umurRef);
    }

    public function getImtUIndeks($imt, $umurBulan, $jenisKelamin)
    {
        $imtData = json_decode(file_get_contents(resource_path('json/imt_u.json')), true)['imt_u'];
        $umurRef = self::getUmurRef($imtData, $umurBulan, $jenisKelamin);
        return self::calculateIndeks($imt, $umurRef);
    }

    public function getTbBbIndeks($tinggiBadan, $beratBadan, $jenisKelamin)
    {
        $tbBbData = json_decode(file_get_contents(resource_path('json/tb_bb.json')), true)['tb_bb'];

        $filterTb = number_format(($tinggiBadan - floor($tinggiBadan) >= 0.5 ? floor($tinggiBadan) + 0.5 : floor($tinggiBadan)), 1, '.', '');

        $jk = $jenisKelamin == 'PEREMPUAN' ? $tbBbData['pr'] : $tbBbData['lk'];

        $cek = $filterTb < 24 ? 'under24' : 'over24';

        $tinggiRef = $jk[$cek][(string) $filterTb] ?? null;

        return self::calculateIndeks($beratBadan, $tinggiRef);
    }

    public function getUmurRef($data, $umurBulan, $jenisKelamin)
    {
        return $jenisKelamin == 'PEREMPUAN' ? $data['pr'][$umurBulan] : $data['lk'][$umurBulan];
    }

    public function calculateIndeks($value, $umurRef)
    {
        if ($umurRef == null) {
            return 0;
        }

        $median = $umurRef[3];
        $difference = abs($value - $median);

        if ($value == $median) {
            $index = 0;
        } elseif ($value > $median) {
            $highLimit = $umurRef[6];
            $oneSD = ($highLimit - $median) / 3;
            $index = $difference / $oneSD;
        } else {
            $lowLimit = $umurRef[0];
            $oneSD = ($lowLimit - $median) / 3;
            $index = $difference / $oneSD;
        }

        return round($index, 1);
    }

    public function getImtKurang6Bulan($beratBadan, $umurBulan)
    {
        return $beratBadan + ($umurBulan * 600); //dalam gram
    }

    public function getImt7sampai12Bulan($beratBadan, $umurBulan)
    {
        return $beratBadan + ($umurBulan * 500); //dalam gram
    }

    public function getImt1sampai5Tahun($umurBulan)
    {
        $tahun = floor($umurBulan / 12);
        $bulan = $umurBulan % 12;
        $usiaDecimal = $tahun + ($bulan / 10);

        $beratBadanIdeal = 2 * $usiaDecimal + 8;

        return $beratBadanIdeal;
    }

    public function getImt($beratBadan = null, $tinggiBadan = null, $umurBulan)
    {
        if ($umurBulan < 6) {
            $imt = self::getImtKurang6Bulan($beratBadan, $umurBulan);
        } elseif ($umurBulan >= 7 && $umurBulan < 12) {
            $imt = self::getImt7sampai12Bulan($beratBadan, $umurBulan);
        } elseif ($umurBulan >= 12 && $umurBulan < 60) {
            $imt = self::getImt1sampai5Tahun($umurBulan);
        } else {
            $imt = $beratBadan / pow($tinggiBadan / 100, 2);
        }

        return round($imt, 1);
    }

    public function getStatusBbU($zScore)
    {
        if ($zScore < -3) {
            return 'Berat Badan sangat kurang (severely underweight)';
        } elseif ($zScore >= -3 && $zScore < -2) {
            return 'Berat Badan kurang (underweight)';
        } elseif ($zScore >= -2 && $zScore <= 1) {
            return 'Berat Badan normal (normal)';
        } elseif ($zScore > 1) {
            return 'Risiko Berat Badan lebih (overweight)';
        } else {
            return 'Tidak diketahui';
        }
    }

    public function getStatusTbU($zScore)
    {
        if ($zScore < -3) {
            return 'Sangat pendek (severely stunted)';
        } elseif ($zScore >= -3 && $zScore < -2) {
            return 'Pendek (stunted)';
        } elseif ($zScore >= -2 && $zScore <= 3) {
            return 'Normal';
        } elseif ($zScore > 3) {
            return 'Tinggi';
        }
    }

    public function getStatusImtU($zScore)
    {
        if ($zScore < -3) {
            return 'Gizi buruk (severely wasted)';
        } elseif ($zScore >= -3 && $zScore < -2) {
            return 'Gizi kurang (wasted)';
        } elseif ($zScore >= -2 && $zScore <= 1) {
            return 'Gizi baik (normal)';
        } elseif ($zScore > 1 && $zScore <= 2) {
            return 'Berisiko gizi lebih (possible risk of overweight)';
        } elseif ($zScore > 2 && $zScore < 3) {
            return 'Gizi lebih (overweight)';
        } elseif ($zScore >= 3) {
            return 'Obesitas (obese)';
        }
    }

    public function getStatusTbBb($zScore)
    {
        if ($zScore < -3) {
            return 'Gizi buruk (severely wasted)';
        } elseif ($zScore >= -3 && $zScore < -2) {
            return 'Gizi kurang (wasted)';
        } elseif ($zScore >= -2 && $zScore <= 1) {
            return 'Gizi baik (normal)';
        } elseif ($zScore > 1 && $zScore <= 2) {
            return 'Berisiko gizi lebih (possible risk of overweight)';
        } elseif ($zScore > 2 && $zScore < 3) {
            return 'Gizi lebih (overweight)';
        } elseif ($zScore >= 3) {
            return 'Obesitas (obese)';
        }
    }
}
