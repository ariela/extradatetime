<?php
namespace ariela;

/**
 * 拡張DateTimeクラス
 */
class ExtraDateTime extends \DateTime
{
    /**
     * 週名の定義(1=日〜7=土)
     * @var array
     */
    private $weekDayNames = array(false, '日', '月', '火', '水', '木', '金', '土');

    /**
     * 特殊な休日を保持する
     * @var array 年->月->日 = 休日名 となるような多段配列となる
     */
    private $offDates = array();

    /**
     * 特殊な休日を設定する
     * @param DateTime $dt 休日のDateTime値
     * @param string $name 休日名
     */
    public function addOffDate(\DateTime $dt, $name)
    {
        $y = intval($dt->format('Y'));
        $m = intval($dt->format('m'));
        $d = intval($dt->format('d'));

        if (!isset($this->offDates[$y])) {
            $this->offDates[$y] = array();
        }
        if (!isset($this->offDates[$y][$m])) {
            $this->offDates[$y][$m] = array();
        }

        $this->offDates[$y][$m][$d] = $name;
    }

    public function addOffDateRange(\DateTime $sdt, \DateTime $edt, $name)
    {
        $im = new \DateInterval('P1D');
        while ($sdt <= $edt) {
            $this->addOffDate($sdt, $name);
            $sdt->add($im);
        }
    }

    /**
     * 祝日名を取得する
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    public function getHolidayName()
    {
        // 祝日のチェック
        $holidayName = $this->checkHoliday();

        // 振替休日のチェック
        $implementHoliday = new \DateTime('1973-04-12');
        if ($holidayName === false && $this->getWeekday() === 2 && $this >= $implementHoliday) {
            $yesterday = clone $this;
            $yesterday->sub(new \DateInterval('P1D'));
            $check = $yesterday->checkHoliday($yesterday);
            if ($check !== false) {
                $holidayName = '振替休日';
            }
        }

        // 特殊休日のチェック
        if ($holidayName === false) {
            $y = $this->getYear();
            $m = $this->getMonth();
            $d = $this->getDay();
            if (!empty($this->offDates[$y]) && !empty($this->offDates[$y][$m]) && !empty($this->offDates[$y][$m][$d])) {
                $holidayName = $this->offDates[$y][$m][$d];
            }
        }

        return $holidayName;
    }

    /**
     * 祝日判定処理
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function checkHoliday()
    {
        $holidayName = false;
        switch ($this->getMonth()) {
            case 1:
                $holidayName = $this->holidayCheckMonth1();
                break;
            case 2:
                $holidayName = $this->holidayCheckMonth2();
                break;
            case 3:
                $holidayName = $this->holidayCheckMonth3();
                break;
            case 4:
                $holidayName = $this->holidayCheckMonth4();
                break;
            case 5:
                $holidayName = $this->holidayCheckMonth5();
                break;
            case 6:
                $holidayName = $this->holidayCheckMonth6();
                break;
            case 7:
                $holidayName = $this->holidayCheckMonth7();
                break;
            case 8:
                $holidayName = $this->holidayCheckMonth8();
                break;
            case 9:
                $holidayName = $this->holidayCheckMonth9();
                break;
            case 10:
                $holidayName = $this->holidayCheckMonth10();
                break;
            case 11:
                $holidayName = $this->holidayCheckMonth11();
                break;
            case 12:
                $holidayName = $this->holidayCheckMonth12();
                break;
        }
        return $holidayName;
    }

    /**
     * 一月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth1()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 元日
        if ($y >= 1949 && $d === 1) {
            return '元日';
        }

        // 成人の日
        if ($y >= 2000 && $this->getWeekOfMonth() === 2 && $this->getWeekday() === 2) {
            // 2000年以降は第2月曜
            return '成人の日';
        } elseif (2000 > $y && $y >= 1949 && $d === 15) {
            return '成人の日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 二月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth2()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 建国記念日
        if ($y >= 1967 && $d === 11) {
            return '建国記念の日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 三月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth3()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 春分の日
        if ($y >= 1949 && $d === $this->getDayOfSpringEquinox()) {
            return '春分の日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 四月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth4()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 昭和の日
        if ($y >= 2007 && $d === 29) {
            return '昭和の日';
        } elseif (2007 > $y && $y >= 1989 && $d === 29) {
            return 'みどりの日';
        } elseif (1989 > $y && $y >= 1949 && $d === 29) {
            return '天皇誕生日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 五月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth5()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 昭和の日
        if ($y >= 1949 && $d === 3) {
            return '憲法記念日';
        }

        // こどもの日
        if ($y >= 1949 && $d === 5) {
            return 'こどもの日';
        }

        // 曜日の取得
        $w = $this->getWeekday();

        // みどりの日
        if ($y >= 2007 && $d === 4) {
            return 'みどりの日';
        } elseif (2007 > $y && $y >= 1986 && $d === 4 && $w > 2) {
            return '国民の休日';
        }

        // 振替休日
        if ($y >= 2007 && $d === 6 && ($w === 3 || $w === 4)) {
            return '振替休日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 六月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth6()
    {
        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 七月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth7()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 海の日
        if ($y >= 2003 && $this->getWeekOfMonth() === 3 && $this->getWeekday() === 2) {
            // 2003年以降は第3月曜
            return '海の日';
        } elseif ($y < 2003 && $y >= 1996 && $d === 20) {
            return '海の日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 八月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth8()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 山の日
        if ($y >= 2016 && $d === 11) {
            return '山の日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 九月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth9()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();
        // 曜日の取得
        $w = $this->getWeekday();
        // 秋分日の取得
        $autumnEquinox = $this->getDayOfAutumnEquinox();

        // 秋分の日
        if ($y >= 1948 && $d === $autumnEquinox) {
            return '秋分の日';
        }

        // 敬老の日
        if ($y >= 2003 && $this->getWeekOfMonth() === 3 && $w === 2) {
            return '敬老の日';
        } elseif ($y < 2003 && $y >= 1966 && $d === 15) {
            return '敬老の日';
        }

        // 国民の休日
        if ($y >= 2003 && $w === 3 && $d === ($autumnEquinox - 1)) {
            return '国民の休日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 十月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth10()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 体育の日
        if ($y >= 2000 && $this->getWeekOfMonth() === 2 && $this->getWeekday() === 2) {
                return '体育の日';
        } elseif ($y < 2000 && $y >= 1966 && $d === 10) {
            return '体育の日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 十一月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth11()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 文化の日
        if ($d === 3) {
            return '文化の日';
        }

        // 勤労感謝の日
        if ($d === 23) {
            return '勤労感謝の日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 十二月の祝日チェックを行う
     * @return string 祝日の場合は祝日名を返す。通常日の場合はFALSEを返す。
     */
    protected function holidayCheckMonth12()
    {
        // 年の取得
        $y = $this->getYear();
        // 日の取得
        $d = $this->getDay();

        // 天皇誕生日
        if ($y >= 1989 && $d === 23) {
            return '天皇誕生日';
        }

        // 条件にハマらない場合は非祝日
        return false;
    }

    /**
     * 月の第何週かを取得する
     */
    protected function getWeekOfMonth()
    {
        return intval(floor(($this->getDay() - 1) / 7) + 1);
    }

    /**
     * 春分/秋分日の簡易計算
     * @param int $year 年
     * @param float $base 計算用パラメータ
     * @param int $fix ガウス用パラメータ年数
     * @return float 春分/秋分日を返します。エラー時はFALSEを返します。
     */
    protected function calculationDayOfEquinox($year, $base)
    {
        return intval(floor($base + (0.242194 * ($year - 1980)) - floor(($year - 1980) / 4)));
    }

    /**
     * 指定した年の春分日を取得する
     * @param int $year 年
     * @return float 春分日を返します。エラー時はFALSEを返します。
     */
    protected function getDayOfSpringEquinox()
    {
        $year = $this->getYear();
        if ($year <= 1947) {
            return false; // 祝日法施行前
        } elseif ($year <= 1979) {
            return $this->calculationDayOfEquinox($year, 20.8357);
        } elseif ($year <= 2099) {
            return $this->calculationDayOfEquinox($year, 20.8431);
        } elseif ($year <= 2150) {
            return $this->calculationDayOfEquinox($year, 21.851);
        }
        return false;
    }

    /**
     * 指定した年の秋分日を取得する
     * @param int $year 年
     * @return float 秋分日を返します。エラー時はFALSEを返します。
     */
    protected function getDayOfAutumnEquinox()
    {
        $year = $this->getYear();
        if ($year <= 1947) {
            return false; // 祝日法施行前
        } elseif ($year <= 1979) {
            return $this->calculationDayOfEquinox($year, 23.2588);
        } elseif ($year <= 2099) {
            return $this->calculationDayOfEquinox($year, 23.2488);
        } elseif ($year <= 2150) {
            return $this->calculationDayOfEquinox($year, 24.2488);
        }
        return false;
    }

    /**
     * 指定されたDateTime値から年を取得する
     * @param DateTime|null $date 年を取得するDateTime値(未指定時はNow扱い)
     * @return int 年
     */
    protected function getYear()
    {
        return intval($this->format('Y'));
    }

    /**
     * 指定されたDateTime値から月を取得する
     * @param DateTime|null $date 月を取得するDateTime値(未指定時はNow扱い)
     * @return int 月
     */
    protected function getMonth()
    {
        return intval($this->format('m'));
    }

    /**
     * 指定されたDateTime値から日を取得する
     * @param DateTime|null $date 日を取得するDateTime値(未指定時はNow扱い)
     * @return int 日
     */
    protected function getDay()
    {
        return intval($this->format('d'));
    }

    /**
     * 指定されたDateTime値から曜日番号を取得する
     * @return int 曜日番号(1=日〜7=土)
     */
    protected function getWeekday()
    {
        return intval($this->format('w')) + 1;
    }

    /**
     * 曜日名を取得する
     * @return string 曜日名
     */
    public function getWeekdayName()
    {
        return $this->weekDayNames[$this->getWeekday()];
    }
}
