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
        $arrInsert = [];
        $i = 0;

        for (; $i < $this->count; $i++) {

            $language = $this->getLanguage();
            $author = $this->getAuthor($language);
            $title = $this->getTitle($language);
            $text = $this->getText($language);
            $date = $this->getDate();
            $likes = $this->getCountLikes();

            $arrInsert[$i] = [$language, $author, $title, $text, $date, $likes];
        }

        Yii::$app->db->createCommand()->batchInsert('post', [
            'language_id', 'author_id', 'title', 'text', 'date', 'like'
        ], $arrInsert);

        return $this->redirect(['/post']);
    }

    /**
     * Получает наименование языка из случайного значения массива
     * @var object $languages
     * @return string
     */
    private function getLanguage()
    {
        $languages = Language::find()->all();
        $arr = $languages[array_rand($languages)];
        $language = $arr->title;

        return $language;
    }

    /**
     * Возвращает массив Авторов на английском или русском языках
     * @param string $language
     * @var object authors
     * @return string
     */
    private function getAuthor($language)
    {
        $authors = Author::find()->indexBy('name')->asArray()->all();

        $author = ($language == 'English') ?
            $this->selLanguage($authors) :
            $this->selLanguage($authors, false);

        return array_rand($author);
    }

    /**
     * Собирает из массива слов предложение
     * @param string $language
     * @function ucfirst_utf8 Позволяет сделать
     *      первую букву заглавной в строке с кириллицей
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
        $dateRand = rand(1, 8);
        $date = strtotime('2017-' . $dateRand . '-' . $dateRand);

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
     * - метод предназначен для выбора заголовка по выбранному языку
     * @param array $fullArray
     * @param boolean $eng
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