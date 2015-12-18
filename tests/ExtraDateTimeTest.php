<?php
/*
 * This file is part of the ExtraDateTime package.
 *
 * (c) Yuki Kisaragi <yuki@transrain.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ariela;

use ariela\ExtraDateTime;

class ExtraDateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testAddOffDate()
    {
        // 準備
        $edt = new ExtraDateTime('2015-11-01');
        $edt->addOffDate(new \DateTime('2015-11-01'), 'Test Holiday');

        $property = new \ReflectionProperty($edt, 'offDates');
        $property->setAccessible(true);

        // 想定される出力結果
        $result = array(2015=>array(11=>array(1=>'Test Holiday')));

        // 出力結果が正しいかどうかをチェック
        $this->assertEquals($result, $property->getValue($edt));

        // 祝日名を取得されるかをチェック
        $this->assertEquals('Test Holiday', $edt->getHolidayName());
    }

    public function testAddOffDateRange()
    {
        // 準備
        $edt = new ExtraDateTime('2015-11-02');
        $edt->addOffDateRange(new \DateTime('2015-11-01'), new \DateTime('2015-11-03'), 'Test Holiday');

        $property = new \ReflectionProperty($edt, 'offDates');
        $property->setAccessible(true);

        // 想定される出力結果
        $result = array(2015=>array(11=>array(
            1=>'Test Holiday',
            2=>'Test Holiday',
            3=>'Test Holiday',
        )));

        // 出力結果が正しいかどうかをチェック
        $this->assertEquals($result, $property->getValue($edt));

        // 祝日名を取得されるかをチェック
        $this->assertEquals('Test Holiday', $edt->getHolidayName());
    }

    public function testGetHolidayName()
    {
        // 出力結果が正しいかどうかをチェック
        // 固定日
        $edt = new ExtraDateTime('2016-01-01');
        $this->assertEquals('元日', $edt->getHolidayName());

        // 第何週-何曜日
        $edt = new ExtraDateTime('2015-01-12');
        $this->assertEquals('成人の日', $edt->getHolidayName());

        // 春分の日
        $edt = new ExtraDateTime('2015-03-21');
        $this->assertEquals('春分の日', $edt->getHolidayName());

        // 秋分の日
        $edt = new ExtraDateTime('2015-09-23');
        $this->assertEquals('秋分の日', $edt->getHolidayName());

        // 振替休日
        $edt = new ExtraDateTime('2015-05-06');
        $this->assertEquals('振替休日', $edt->getHolidayName());
    }

    public function testGetYear()
    {
        // 準備
        $edt = new ExtraDateTime('2015-11-02');
        $interval = new \DateInterval('P1Y');
        $method = new \ReflectionMethod($edt, 'getYear');
        $method->setAccessible(true);

        // 出力結果が正しいかどうかをチェック
        $this->assertEquals(2015, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2016, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2017, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2018, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2019, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2020, $method->invoke($edt));
    }

    public function testGetMonth()
    {
        // 準備
        $edt = new ExtraDateTime('2015-11-02');
        $interval = new \DateInterval('P1M');
        $method = new \ReflectionMethod($edt, 'getMonth');
        $method->setAccessible(true);

        // 出力結果が正しいかどうかをチェック
        $this->assertEquals(11, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(12, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(1, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(3, $method->invoke($edt));
    }

    public function testGetDay()
    {
        // 準備
        $edt = new ExtraDateTime('2015-11-29');
        $interval = new \DateInterval('P1D');
        $method = new \ReflectionMethod($edt, 'getDay');
        $method->setAccessible(true);

        // 出力結果が正しいかどうかをチェック
        $this->assertEquals(29, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(30, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(1, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(3, $method->invoke($edt));
    }

    public function testGetWeekday()
    {
        // 準備
        $edt = new ExtraDateTime('2015-11-02');
        $interval = new \DateInterval('P1D');
        $method = new \ReflectionMethod($edt, 'getWeekday');
        $method->setAccessible(true);

        // 出力結果が正しいかどうかをチェック
        $this->assertEquals(2, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(3, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(4, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(5, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(6, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(7, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(1, $method->invoke($edt));

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals(2, $method->invoke($edt));
    }

    public function testGetWeekdayName()
    {
        // 準備
        $edt = new ExtraDateTime('2015-11-02');
        $interval = new \DateInterval('P1D');

        // 出力結果が正しいかどうかをチェック
        $this->assertEquals('月', $edt->getWeekdayName());

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals('火', $edt->getWeekdayName());

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals('水', $edt->getWeekdayName());

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals('木', $edt->getWeekdayName());

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals('金', $edt->getWeekdayName());

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals('土', $edt->getWeekdayName());

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals('日', $edt->getWeekdayName());

        // 出力結果が正しいかどうかをチェック
        $edt->add($interval);
        $this->assertEquals('月', $edt->getWeekdayName());
    }

    public function testHolidayCheckMonth1()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth1');
        $method->setAccessible(true);

        // 元旦
        $edt->setTimestamp(strtotime('2016-01-01'));
        $this->assertEquals('元日', $edt->getHolidayName());

        // 成人の日
        $edt->setTimestamp(strtotime('1999-01-15'));
        $this->assertEquals('成人の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2000-01-10'));
        $this->assertEquals('成人の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2000-01-15'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2016-01-11'));
        $this->assertEquals('成人の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth2()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth2');
        $method->setAccessible(true);

        // 建国記念の日
        $edt->setTimestamp(strtotime('2016-02-11'));
        $this->assertEquals('建国記念の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth3()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth3');
        $method->setAccessible(true);

        // 春分の日
        $edt->setTimestamp(strtotime('2015-03-20'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2015-03-21'));
        $this->assertEquals('春分の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2016-03-20'));
        $this->assertEquals('春分の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2017-03-20'));
        $this->assertEquals('春分の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth4()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth4');
        $method->setAccessible(true);

        // 昭和の日
        $edt->setTimestamp(strtotime('1988-04-29'));
        $this->assertEquals('天皇誕生日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('1989-04-29'));
        $this->assertEquals('みどりの日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2006-04-29'));
        $this->assertEquals('みどりの日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2007-04-29'));
        $this->assertEquals('昭和の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth5()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth5');
        $method->setAccessible(true);

        // 憲法記念日
        $edt->setTimestamp(strtotime('2007-05-03'));
        $this->assertEquals('憲法記念日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2015-05-03'));
        $this->assertEquals('憲法記念日', $edt->getHolidayName());

        // こどもの日
        $edt->setTimestamp(strtotime('2007-05-05'));
        $this->assertEquals('こどもの日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2015-05-05'));
        $this->assertEquals('こどもの日', $edt->getHolidayName());

        // 国民の休日
        //日曜日の場合は､只の日曜日（≠祝日）
        $edt->setTimestamp(strtotime('2003-05-04'));
        $this->assertEquals(false, $edt->getHolidayName());

        //月曜日の場合は憲法記念日の振替休日
        $edt->setTimestamp(strtotime('1992-05-04'));
        $this->assertEquals("振替休日", $edt->getHolidayName());

        //火～土曜日の場合に「国民の休日」
        $edt->setTimestamp(strtotime('2004-05-04'));
        $this->assertEquals("国民の休日", $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2005-05-04'));
        $this->assertEquals("国民の休日", $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2000-05-04'));
        $this->assertEquals("国民の休日", $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2001-05-04'));
        $this->assertEquals("国民の休日", $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2002-05-04'));
        $this->assertEquals("国民の休日", $edt->getHolidayName());

        // 2007年以降はみどりの日
        $edt->setTimestamp(strtotime('2006-05-04'));
        $this->assertEquals('国民の休日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2007-05-04'));
        $this->assertEquals('みどりの日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth6()
    {
        // 準備
        $edt = new ExtraDateTime();
        $interval = new \DateInterval('P1D');
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth6');
        $method->setAccessible(true);

        // 6月は祝日が無い
        $edt->setTimestamp(strtotime('2015-06-01'));
        for ($i=0; $i<31; $i++) {
            $this->assertEquals(false, $edt->getHolidayName());
            $edt->add($interval);
        }
    }

    public function testHolidayCheckMonth7()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth7');
        $method->setAccessible(true);

        // 海の日
        $edt->setTimestamp(strtotime('1995-07-20'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2002-07-20'));
        $this->assertEquals('海の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2003-07-20'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2003-07-21'));
        $this->assertEquals('海の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth8()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth8');
        $method->setAccessible(true);

        // 山の日
        $edt->setTimestamp(strtotime('2015-08-11'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2016-08-11'));
        $this->assertEquals('山の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth9()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth9');
        $method->setAccessible(true);

        // 敬老の日
        $edt->setTimestamp(strtotime('2002-09-15'));
        $this->assertEquals('敬老の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2003-09-15'));
        $this->assertEquals('敬老の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2014-09-15'));
        $this->assertEquals('敬老の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2015-09-21'));
        $this->assertEquals('敬老の日', $edt->getHolidayName());

        // 秋分の日
        $edt->setTimestamp(strtotime('2012-09-22'));
        $this->assertEquals('秋分の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2012-09-23'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2015-09-23'));
        $this->assertEquals('秋分の日', $edt->getHolidayName());

        // 国民の休日
        $edt->setTimestamp(strtotime('2009-09-22'));
        $this->assertEquals('国民の休日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2015-09-22'));
        $this->assertEquals('国民の休日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth10()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth10');
        $method->setAccessible(true);

        // 体育の日
        $edt->setTimestamp(strtotime('1999-10-10'));
        $this->assertEquals('体育の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2000-10-10'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2000-10-09'));
        $this->assertEquals('体育の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth11()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth11');
        $method->setAccessible(true);

        // 文化の日
        $edt->setTimestamp(strtotime('2015-11-03'));
        $this->assertEquals('文化の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2016-11-03'));
        $this->assertEquals('文化の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2017-11-03'));
        $this->assertEquals('文化の日', $edt->getHolidayName());

        // 勤労感謝の日
        $edt->setTimestamp(strtotime('2015-11-23'));
        $this->assertEquals('勤労感謝の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2016-11-23'));
        $this->assertEquals('勤労感謝の日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2017-11-23'));
        $this->assertEquals('勤労感謝の日', $edt->getHolidayName());
    }

    public function testHolidayCheckMonth12()
    {
        // 準備
        $edt = new ExtraDateTime();
        $method = new \ReflectionMethod($edt, 'holidayCheckMonth12');
        $method->setAccessible(true);

        // 天皇誕生日
        $edt->setTimestamp(strtotime('1988-12-23'));
        $this->assertEquals(false, $edt->getHolidayName());

        $edt->setTimestamp(strtotime('1989-12-23'));
        $this->assertEquals('天皇誕生日', $edt->getHolidayName());

        $edt->setTimestamp(strtotime('2015-12-23'));
        $this->assertEquals('天皇誕生日', $edt->getHolidayName());
    }
}
