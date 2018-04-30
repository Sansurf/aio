<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Language;
use app\models\Author;


/**
 * Class GeneratorController Реализует автогенерирование постов
 * @var int $count Кол-во генерируемых записей
 * @package app\controllers
 */
class GeneratorController extends Controller
{
    public $count = 10; //TODO: Добавить поле для ввода
    private $titles = [
        'rus' => ["жесть", "удивительно", "снова", "совсем", "шок", "случай", "сразу", "событие", "начало", "вирус"],
        'eng' => ["currency", "amazing", "again", "absolutely", "shocking", "case", "immediately", "event", "beginning", "virus"]
    ];
    private $texts = [
        'rus' => ["один", "еще", "бы", "такой", "только", "себя", "свое", "какой", "когда", "уже", "для", "вот", "кто", "да", "говорить", "год", "знать", "мой", "до", "или", "если", "время", "рука", "нет", "самый", "ни", "стать", "большой", "даже", "другой", "наш", "свой", "ну", "под", "где", "дело", "есть", "сам", "раз", "чтобы", "два", "там", "чем", "глаз", "жизнь", "первый", "день", "тута", "во", "ничто", "потом", "очень", "со", "хотеть", "ли", "при", "голова", "надо", "без", "видеть", "идти", "теперь", "тоже", "стоять", "друг", "дом", "сейчас", "можно", "после", "слово", "здесь", "думать", "место", "спросить", "через", "лицо", "что", "тогда", "ведь", "хороший", "каждый", "новый", "жить", "должный", "смотреть", "почему", "потому", "сторона", "просто", "нога", "сидеть", "понять", "иметь", "конечный", "делать", "вдруг", "над", "взять", "никто", "сделать"],
        'eng' => ["one", "yet", "would", "such", "only", "yourself", "his", "what", "when", "already", "for", "behold", "Who", "yes", "speak", "year", "know", "my", "before", "or", "if", "time", "arm", "no", "most", "nor", "become", "big", "even", "other", "our", "his", "well", "under", "where", "a business", "there is", "himself", "time", "that", "two", "there", "than", "eye", "a life", "first", "day", "mulberry", "in", "nothing", "later", "highly", "with", "to want", "whether", "at", "head", "need", "without", "see", "go", "now", "also", "stand", "friend", "house", "now", "can", "after", "word", "here", "think", "a place", "ask", "across", "face", "what", "then", "after all", "good", "each", "new", "live", "due", "look", "why", "because", "side", "just", "leg", "sit", "understand", "have", "finite", "do", "all of a sudden", "above", "to take", "no one", "make"]
    ];


    /**
     * Формирует запрос в БД
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        // получение массивов из БД
        $languages = Language::find()->all();
        $authorNames = Author::find()->indexBy('name')->all();

        $arrInsert = [];
        $i = 0;

        for (; $i < $this->count; $i++) {

            $lang = $this->lang($languages)->title;
            $language = $this->lang($languages)->id;
            $author = $this->getAuthor($authorNames, $lang);
            $title = $this->getTitle($lang);
            $text = $this->getText($lang);
            $date = $this->getDate();
            $likes = $this->getCountLikes();

            $arrInsert[$i] = [$language, $author, $title, $text, $date, $likes];
        }

        Yii::$app->db->createCommand()->batchInsert('post', [
            'language_id', 'author_id', 'title', 'text', 'date', 'like'
        ], $arrInsert)->execute();

        return $this->redirect(['/post']);
    }

    /**
     * Выбор языка
     * @var object $languages
     * @return array
     */
    private function lang($arr)
    {
        $arrRand = $arr[array_rand($arr)];
        $language = $arrRand;

        return $language;
    }

    /**
     * Получает ID автора в зависимости от языка
     * @param string $lang
     * @param array $arr
     * @return int
     */
    private function getAuthor($arr, $lang)
    {
        $author = ($lang == 'English') ?
            $this->selLanguage($arr) :
            $this->selLanguage($arr, false);
        $author = array_rand($author);

        return $arr[$author]['id'];
    }

    /**
     * Собирает из массива слов предложение
     * @param string $language
     * @return string
     */
    private function getTitle($language)
    {
        $lang = ($language == 'English') ? 'eng' : 'rus';

        $arrRand = array_rand(array_flip($this->titles[$lang]), rand(4, 6));
        shuffle($arrRand);
        $title = ucfirst_utf8(implode(" ", $arrRand));

        return $title;
    }

    /**
     * Получает текст поста
     * @param string $language
     * @return string
     */
    private function getText($language)
    {
        $lang = ($language == 'English') ? 'eng' : 'rus';

        $text = '';
        $sentenceCount = rand(3, 4);
        for ($i = 0; $i < $sentenceCount; $i++) {

            // Формирование одного предложения
            $arrRand = array_rand(array_flip($this->texts[$lang]), rand(5, 8));
            shuffle($arrRand);
            $oneSentence = ucfirst_utf8(implode(" ", $arrRand)) . ". ";

            $text .= $oneSentence;
        }

        return $text;
    }

    /**
     * Выбирает случайную дату из диапазона
     * @return false|int
     */
    private function getDate()
    {
        $mRand = rand(1, 8);
        $dRand = rand(1, 8);
        $date = date('2017-' . $mRand . '-' . $dRand);

        return $date;
    }

    /**
     * Случайное число лайков
     * @return int
     */
    private function getCountLikes()
    {
        return rand(0, 100);
    }

    /**
     * Выбор из массива английских/русских слов
     * @param array $fullArray
     * @param bool $eng
     * @return array $sliceArray
     */
    private function selLanguage($fullArray, $eng = true)
    {
        $sliceArray = ($eng) ?
            array_intersect_key($fullArray,
                array_flip(preg_grep('/^[a-z]*$/i', array_keys($fullArray)))
            ) :
            array_intersect_key($fullArray,
                array_flip(preg_grep('/^[a-z]*$/i', array_keys($fullArray), 1))
            );

        return $sliceArray;
    }
}