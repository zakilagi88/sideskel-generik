<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum EtnisSukuType: string implements HasLabel
{

        //referensi https://id.wikipedia.org/wiki/Daftar_suku_bangsa_di_Indonesia_menurut_jumlah_penduduk

    case JAWA = "JAWA";
    case SUNDA = "SUNDA";
    case MELAYU = "MELAYU";
    case BATAK = "BATAK";
    case MADURA = "MADURA";
    case BETAWI = "BETAWI";
    case MINANGKABAU = "MINANGKABAU";
    case BUGIS = "BUGIS";
    case BANTEN = "BANTEN";
    case BANJAR = "BANJAR";
    case BALI = "BALI";
    case ACEH = "ACEH";
    case DAYAK = "DAYAK";
    case SASAK = "SASAK";
    case TIONGHOA = "TIONGHOA";
    case MAKASSAR = "MAKASSAR";
    case CIREBON = "CIREBON";
    case LAMPUNG = "LAMPUNG";
    case PALEMBANG = "PALEMBANG";
    case GORONTALO = "GORONTALO";
    case MINAHASA = "MINAHASA";
    case NIAS = "NIAS";
    case BUTON = "BUTON";
    case ATONI = "ATONI";
    case TORAJA = "TORAJA";
    case KAILI = "KAILI";
    case MANGGARAI = "MANGGARAI";
    case OGAN = "OGAN";
    case MANDAR = "MANDAR";
    case BANGKA = "BANGKA";
    case BIMA = "BIMA";
    case SUMBA = "SUMBA";
    case MUSI = "MUSI";
    case DANI = "DANI";
    case SANGIR = "SANGIR";
    case REJANG = "REJANG";
    case AMBON = "AMBON";
    case TOLAKI = "TOLAKI";
    case LUWU = "LUWU";
    case SUMBAWA = "SUMBAWA";
    case KOMERING = "KOMERING";
    case GAYO = "GAYO";
    case MUNA = "MUNA";
    case MEE = "MEE";
    case MONGONDOW = "MONGONDOW";
    case KERINCI = "KERINCI";
    case LAMAHOLOT = "LAMAHOLOT";
    case NGADA = "NGADA";
    case OSING = "OSING";
    case KUTAI = "KUTAI";
    case TIMOR_LESTE = "TIMOR_LESTE";
    case FLORES = "FLORES";
    case BAJAU = "BAJAU";
    case ROTE = "ROTE";
    case DURI = "DURI";
    case KEI = "KEI";
    case BIAK = "BIAK";
    case BELITUNG = "BELITUNG";
    case ALOR = "ALOR";
    case SERAM = "SERAM";
    case RAWAS = "RAWAS";
    case LIO = "LIO";
    case PAMONA = "PAMONA";
    case SAWU = "SAWU";
    case BANGGAI = "BANGGAI";
    case ENIM = "ENIM";
    case LEMBAK = "LEMBAK";
    case RAMBANG = "RAMBANG";
    case NGALIK = "NGALIK";
    case MAMASA = "MAMASA";
    case TERNATE = "TERNATE";
    case ASMAT = "ASMAT";
    case SELAYAR = "SELAYAR";
    case MBOJO = "MBOJO";
    case DAYA = "DAYA";
    case BUOL = "BUOL";
    case ARAB = "ARAB";
    case TOBELO = "TOBELO";
    case TANIMBAR = "TANIMBAR";
    case MAMUJU = "MAMUJU";
    case GALELA = "GALELA";
    case YAPEN = "YAPEN";
    case DAUWA = "DAUWA";
    case ALAS = "ALAS";
    case SALUAN = "SALUAN";
    case TALAUD = "TALAUD";
    case POSO_PESISIR = "POSO_PESISIR";
    case TOMINI = "TOMINI";
    case MAKIAN = "MAKIAN";
    case SAPARUA = "SAPARUA";
    case TIDORE = "TIDORE";
    case SULA = "SULA";
    case BAWEAN = "BAWEAN";
    case ARFAK = "ARFAK";
    case PASIR = "PASIR";
    case LAUJE = "LAUJE";
    case MENTAWAI = "MENTAWAI";
    case SIMEULUE = "SIMEULUE";
    case ANEUK_JAMEE = "ANEUK_JAMEE";
    case MONI = "MONI";
    case DOMPU = "DOMPU";
    case BURU = "BURU";
    case SINGKIL = "SINGKIL";
    case TAMIANG = "TAMIANG";
    case AIFAT = "AIFAT";
    case MARIRI = "MARIRI";
    case TAA = "TAA";
    case MUKO_MUKO = "MUKO_MUKO";
    case KETENGBAN = "KETENGBAN";
    case TIALO = "TIALO";
    case KAUR = "KAUR";
    case MORONENE = "MORONENE";
    case MARIND_ANIM = "MARIND_ANIM";
    case PATTAE = "PATTAE";
    case GESER_GOROM = "GESER_GOROM";
    case HARUKU = "HARUKU";
    case ARU = "ARU";
    case SENTANI = "SENTANI";
    case NGALUM = "NGALUM";
    case PEKAL = "PEKAL";
    case MIMIKA = "MIMIKA";
    case LOLODA = "LOLODA";
    case KISAR = "KISAR";
    case AKIT = "AKIT";
    case BABAR = "BABAR";
    case HUBULA = "HUBULA";
    case WAROPEN = "WAROPEN";
    case MBAHAM = "MBAHAM";
    case BALI_AGA = "BALI_AGA";
    case TOBARU = "TOBARU";
    case BANDA = "BANDA";
    case KAO = "KAO";
    case DAMAL = "DAMAL";
    case MOI = "MOI";
    case YAGHAY = "YAGHAY";
    case PATANI = "PATANI";
    case KALUMPANG = "KALUMPANG";
    case WANDAMEN = "WANDAMEN";
    case TEHIT = "TEHIT";
    case SUWAWA = "SUWAWA";
    case KORE = "KORE";
    case IRARUTU = "IRARUTU";
    case KOKODA = "KOKODA";
    case INANWATAN = "INANWATAN";
    case WAMESA = "WAMESA";
    case ATINGGOLA = "ATINGGOLA";
    case LAIN_LAINNYA = "LAIN_LAINNYA";



    public function getLabel(): ?string
    {
        return match ($this) {
            self::JAWA => 'Jawa',
            self::SUNDA => 'Sunda',
            self::MELAYU => 'Melayu',
            self::BATAK => 'Batak',
            self::MADURA => 'Madura',
            self::BETAWI => 'Betawi',
            self::MINANGKABAU => 'Minangkabau',
            self::BUGIS => 'Bugis',
            self::BANTEN => 'Banten',
            self::BANJAR => 'Banjar',
            self::BALI => 'Bali',
            self::ACEH => 'Aceh',
            self::DAYAK => 'Dayak',
            self::SASAK => 'Sasak',
            self::TIONGHOA => 'Tionghoa',
            self::MAKASSAR => 'Makassar',
            self::CIREBON => 'Cirebon',
            self::LAMPUNG => 'Lampung',
            self::PALEMBANG => 'Palembang',
            self::GORONTALO => 'Gorontalo',
            self::MINAHASA => 'Minahasa',
            self::NIAS => 'Nias',
            self::BUTON => 'Buton',
            self::ATONI => 'Atoni',
            self::TORAJA => 'Toraja',
            self::KAILI => 'Kaili',
            self::MANGGARAI => 'Manggarai',
            self::OGAN => 'Ogan',
            self::MANDAR => 'Mandar',
            self::BANGKA => 'Bangka',
            self::BIMA => 'Bima',
            self::SUMBA => 'Sumba',
            self::MUSI => 'Musi',
            self::DANI => 'Dani',
            self::SANGIR => 'Sangir',
            self::REJANG => 'Rejang',
            self::AMBON => 'Ambon',
            self::TOLAKI => 'Tolaki',
            self::LUWU => 'Luwu',
            self::SUMBAWA => 'Sumbawa',
            self::KOMERING => 'Komering',
            self::GAYO => 'Gayo',
            self::MUNA => 'Muna',
            self::MEE => 'Mee',
            self::MONGONDOW => 'Mongondow',
            self::KERINCI => 'Kerinci',
            self::LAMAHOLOT => 'Lamaholot',
            self::NGADA => 'Ngada',
            self::OSING => 'Osing',
            self::KUTAI => 'Kutai',
            self::TIMOR_LESTE => 'Timor Leste',
            self::FLORES => 'Flores',
            self::BAJAU => 'Bajau',
            self::ROTE => 'Rote',
            self::DURI => 'Duri',
            self::KEI => 'Kei',
            self::BIAK => 'Biak',
            self::BELITUNG => 'Belitung',
            self::ALOR => 'Alor',
            self::SERAM => 'Seram',
            self::RAWAS => 'Rawas',
            self::LIO => 'Lio',
            self::PAMONA => 'Pamona',
            self::SAWU => 'Sawu',
            self::BANGGAI => 'Banggai',
            self::ENIM => 'Enim',
            self::LEMBAK => 'Lembak',
            self::RAMBANG => 'Rambang',
            self::NGALIK => 'Ngalik',
            self::MAMASA => 'Mamasa',
            self::TERNATE => 'Ternate',
            self::ASMAT => 'Asmat',
            self::SELAYAR => 'Selayar',
            self::MBOJO => 'Mbojo',
            self::DAYA => 'Daya',
            self::BUOL => 'Buol',
            self::ARAB => 'Arab',
            self::TOBELO => 'Tobelo',
            self::TANIMBAR => 'Tanimbar',
            self::MAMUJU => 'Mamuju',
            self::GALELA => 'Galela',
            self::YAPEN => 'Yapen',
            self::DAUWA => 'Dauwa',
            self::ALAS => 'Alas',
            self::SALUAN => 'Saluan',
            self::TALAUD => 'Talaud',
            self::POSO_PESISIR => 'Poso Pesisir',
            self::TOMINI => 'Tomini',
            self::MAKIAN => 'Makian',
            self::SAPARUA => 'Saparua',
            self::TIDORE => 'Tidore',
            self::SULA => 'Sula',
            self::BAWEAN => 'Bawean',
            self::ARFAK => 'Arfak',
            self::PASIR => 'Pasir',
            self::LAUJE => 'Lauje',
            self::MENTAWAI => 'Mentawai',
            self::SIMEULUE => 'Simeulue',
            self::ANEUK_JAMEE => 'Aneuk Jamee',
            self::MONI => 'Moni',
            self::DOMPU => 'Dompu',
            self::BURU => 'Buru',
            self::SINGKIL => 'Singkil',
            self::TAMIANG => 'Tamiang',
            self::AIFAT => 'Aifat',
            self::MARIRI => 'Mariri',
            self::TAA => 'Taa',
            self::MUKO_MUKO => 'Muko-Muko',
            self::KETENGBAN => 'Ketengban',
            self::TIALO => 'Tialo',
            self::KAUR => 'Kaur',
            self::MORONENE => 'Moronene',
            self::MARIND_ANIM => 'Marind Anim',
            self::PATTAE => 'Pattae',
            self::GESER_GOROM => 'Geser-Gorom',
            self::HARUKU => 'Haruku',
            self::ARU => 'Aru',
            self::SENTANI => 'Sentani',
            self::NGALUM => 'Ngalum',
            self::PEKAL => 'Pekal',
            self::MIMIKA => 'Mimika',
            self::LOLODA => 'Loloda',
            self::KISAR => 'Kisar',
            self::AKIT => 'Akit',
            self::BABAR => 'Babar',
            self::HUBULA => 'Hubula',
            self::WAROPEN => 'Waropen',
            self::MBAHAM => 'Mbaham',
            self::BALI_AGA => 'Bali Aga',
            self::TOBARU => 'Tobaru',
            self::BANDA => 'Banda',
            self::KAO => 'Kao',
            self::DAMAL => 'Damal',
            self::MOI => 'Moi',
            self::YAGHAY => 'Yaghay',
            self::PATANI => 'Patani',
            self::KALUMPANG => 'Kalumpang',
            self::WANDAMEN => 'Wandamen',
            self::TEHIT => 'Tehit',
            self::SUWAWA => 'Suwawa',
            self::KORE => 'Kore',
            self::IRARUTU => 'Irarutu',
            self::KOKODA => 'Kokoda',
            self::INANWATAN => 'Inanwatan',
            self::WAMESA => 'Wamesa',
            self::ATINGGOLA => 'Atinggola',
            self::LAIN_LAINNYA => 'Lain-lainnya',
        };
    }
}
