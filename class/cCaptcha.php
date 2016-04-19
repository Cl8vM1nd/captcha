<?php
######################################/*
#									 #
#		CREATED BY ILYA FAIST 		 #
#			  04.2015				 #
#									 #
######################################*/							


class cCaptcha
{
	private static $letterList 		= /*'abcdefghijkmnpqrstuvwxyz*/'1234567890';			// Список символов для капчи
	private static $salt			= 'fYsh%#3H6vF%8oo4Fn&!35B8#-';							// Соль для md5(капчи)

	/*		
	ДОСТУПНЫЕ ВАРИАНТЫ УРОВНЕЙ:
	-		 EASY
	-		 MIDDLE
	-		 HARD
	-		 HARDCORE
	-		 MANUAL
	*/
	private static $LEVEL   		= 'MIDDLE';

	private static $randFigure 		= 0;											// Установка правил для фона капчи(переопределяется в init)
	/*		0 - показываются случайно квадраты и треугольники
			1 - показываются только квадраты
			2 - показываются только треугольники
	*/

	private static $randRegister 	= true;											// Случайный регистр для каждой буквы

	private static $distort			= true;											// Добавлять ли дисторшн на изображение(Серьезно уменьшает возможность распознавания)

	private static $minDistortX 	= 35;											// Минимальный дисторшн по X 
	private static $maxDistortX 	= 40;											// Максимальный дисторшн по X 

	private static $minDistortY 	= 35;											// Минимальный дисторшн по Y 
	private static $maxDistortY 	= 40;											// Максимальный дисторшн по Y 

	private static $captchaW 		= 150;											// Ширинка капчи
	private static $captchaH 		= 70;											// Высота капчи

	private static $minLength		= 5;											// Стандартная минимальная длина капчи
	private static $maxLength		= 5;											// Стандартная максимальная длина капчи

	private static $fontPath 		= 'fonts/';										// Путь к шрифтам капчи

	private static $fMinSize		= 17;											// Минимальный размер для шрифтов
	private static $fMaxSize		= 25;											// Максимальный размер для шрифтов

	private static $minLine 		= 2;											// Минимальное кол-во линий
	private static $maxLine 		= 3;											// Максимальное кол-во линий

	private static $wQuadCountMin 	= 2;											// Минимальное кол-во квадратов в ширину
	private static $wQuadCountMax 	= 4;											// Максимальное кол-во квадратов в ширину

	private static $hQuadCountMin 	= 2;											// Минимальное кол-во квадратов в высоту
	private static $hQuadCountMax 	= 2;											// Максимальное кол-во квадратов в высоту

	private static $wTriaCountMin 	= 2;											// Минимальное кол-во треугольников в ширину
	private static $wTriaCountMax 	= 3;											// Максимальное кол-во треугольников в ширину

	private static $hTriaCountMin 	= 2;											// Минимальное кол-во треугольников в высоту
	private static $hTriaCountMax 	= 2;											// Максимальное кол-во треугольников в высоту


	private static $textColors 		= array(										// Возможные цвета текста капчи(Важно, влияет на читабельность)
					    	'white' => array(
					     	   210,210,210
					      	  ),
					   		 'black' => array(
					     	   40,40,40
					       	  )
					);	

	private static $minQuadColor 	= 60;											// Минимальный цвет для квадратов
	private static $maxQuadColor 	= 170;											// Максимальный цвет для квадратов

	private static $minTriaColor 	= 50;											// Минимальный цвет для треугольников
	private static $maxTriaColor 	= 180;											// Максимальный цвет для треугольников

	private static $minLineColor 	= 0;											// Минимальный цвет для линий
	private static $maxLineColor 	= 255;											// Максимальный цвет для линий

	private static $lMinX			= 0;											// Минимальная координата X для линии
	private static $lMaxX			= 100;											// Максимальная координата X для линии(Переопределяется в init())

	private static $lMinY			= 0;											// Минимальная координата Y для линии
	private static $lMaxY			= 50;											// Максимальная координата Y для линии(Переопределяется в init())

	private static $tMinStartX		= 0;											// Минимальная координата X для первого символа капчи
	private static $tMaxStartX		= 3;											// Максимальная координата X для первого символа капчи

	private static $tMinStartY		= 36;											// Минимальная возможная позиция символов по Y
	private static $tMaxStartY		= 55;											// Максимальная возможная позиция символов по Y

	private static $letterSpace 	= 25;											// Расстояние между символами капчи
	private static $minAngle		= -25;											// Минимальный угол вращения для символов капчи
	private static $maxAngle		= 25;											// Максимальный угол вращения для символов капчи


	private static $init 			= false;										// Статус инициализации
	private static $img;															// Изображение капчи
	private static $captcha 		= '';											// Капча



	/*		Установка стандартных параметров в зависимости от уровня		
		
			ВСЕ НАСТРОЙКИ МОГУТ БЫТЬ ПЕРЕОПРЕЕЛЕННЫЕ ЗДЕСЬ!

	*/

	public static function init($level) 
	{
		self::$init = true;
		switch ($level) {
			case 'EASY': 													
				/*		
				0 - показываются случайно квадраты и треугольники
				1 - показываются только квадраты
				2 - показываются только треугольники
				*/
				self::$randFigure 		= 1;											// Установка правил для фона капчи
				self::$distort 			= false;										// Добавлять ли дисторшн на изображение(Серьезно уменьшает возможность распознавания)
				self::$fontPath  	   .= 'easy/';										// Путь к шрифтам капчи
				self::$minLength		= 4;											// Стандартная минимальная длина капчи					
				self::$maxLength		= 4;											// Стандартная максимальная длина капчи

				self::$randRegister 	= false;										// Случайный регистр для каждой буквы

				self::$minDistortX 		= 35;											// Минимальный дисторшн по X 
				self::$maxDistortX 		= 40;											// Максимальный дисторшн по X 

				self::$minDistortY 		= 35;											// Минимальный дисторшн по Y 
				self::$maxDistortY 		= 40;											// Максимальный дисторшн по Y 

				self::$fMinSize			= 20;											// Минимальный размер для шрифтов
				self::$fMaxSize			= 25;											// Максимальный размер для шрифтов

				self::$minLine 			= 2;											// Минимальное кол-во линий
				self::$maxLine 			= 3;											// Максимальное кол-во линий

				self::$wQuadCountMin 	= 2;											// Минимальное кол-во квадратов в ширину
				self::$wQuadCountMax 	= 4;											// Максимальное кол-во квадратов в ширину

				self::$hQuadCountMin 	= 1;											// Минимальное кол-во квадратов в высоту
				self::$hQuadCountMax 	= 2;											// Максимальное кол-во квадратов в высоту

				self::$wTriaCountMin 	= 2;											// Минимальное кол-во треугольников в ширину
				self::$wTriaCountMax 	= 3;											// Максимальное кол-во треугольников в ширину

				self::$hTriaCountMin 	= 1;											// Минимальное кол-во треугольников в высоту
				self::$hTriaCountMax 	= 2;											// Максимальное кол-во треугольников в высоту

				self::$minQuadColor 	= 60;											// Минимальный цвет для квадратов
				self::$maxQuadColor 	= 170;											// Максимальный цвет для квадратов

				self::$minTriaColor 	= 50;											// Минимальный цвет для треугольников
				self::$maxTriaColor 	= 180;											// Максимальный цвет для треугольников

				self::$minLineColor 	= 0;											// Минимальный цвет для линий
				self::$maxLineColor 	= 255;											// Максимальный цвет для линий

				self::$lMinX			= 0;											// Минимальная координата X для линии
				self::$lMaxX 	 		= self::$captchaW;								// Максимальная координата X для линии

				self::$lMinY			= 0;											// Минимальная координата Y для линии
				self::$lMaxY 	 		= self::$captchaH;								// Максимальная координата Y для линии

				self::$tMinStartX		= 0;											// Минимальная координата X для первого символа капчи
				self::$tMaxStartX		= 3;											// Максимальная координата X для первого символа капчи

				self::$tMinStartY		= 36;											// Минимальная возможная позиция символов по Y
				self::$tMaxStartY		= 55;											// Максимальная возможная позиция символов по Y

				self::$letterSpace 		= 25;											// Расстояние между символами капчи
				self::$minAngle			= -20;											// Минимальный угол вращения для символов капчи
				self::$maxAngle			= 20;											// Максимальный угол вращения для символов капчи


				self::$textColors 		= array(										// Возможные цвета текста капчи(Важно, влияет на читабельность)
				    	'white' => array(
				     	   210,210,210
				      	  ),
				   		 'black' => array(
				     	   40,40,40
				       	  )
				);	

			break;
			case 'MIDDLE': 
				/*		
				0 - показываются случайно квадраты и треугольники
				1 - показываются только квадраты
				2 - показываются только треугольники
				*/
				self::$randFigure 		= 0;											// Установка правил для фона капчи
				self::$distort 			= true;											// Добавлять ли дисторшн на изображение(Серьезно уменьшает возможность распознавания)
				self::$fontPath  	   .= 'middle/';									// Путь к шрифтам капчи
				self::$minLength		= 4;											// Стандартная минимальная длина капчи					
				self::$maxLength		= 4;											// Стандартная максимальная длина капчи

				self::$randRegister 	= true;											// Случайный регистр для каждой буквы

				self::$minDistortX 		= 35;											// Минимальный дисторшн по X 
				self::$maxDistortX 		= 40;											// Максимальный дисторшн по X 

				self::$minDistortY 		= 35;											// Минимальный дисторшн по Y 
				self::$maxDistortY 		= 40;											// Максимальный дисторшн по Y 

				self::$fMinSize			= 20;											// Минимальный размер для шрифтов
				self::$fMaxSize			= 35;											// Максимальный размер для шрифтов

				self::$minLine 			= 2;											// Минимальное кол-во линий
				self::$maxLine 			= 4;											// Максимальное кол-во линий

				self::$wQuadCountMin 	= 2;											// Минимальное кол-во квадратов в ширину
				self::$wQuadCountMax 	= 4;											// Максимальное кол-во квадратов в ширину

				self::$hQuadCountMin 	= 1;											// Минимальное кол-во квадратов в высоту
				self::$hQuadCountMax 	= 2;											// Максимальное кол-во квадратов в высоту

				self::$wTriaCountMin 	= 2;											// Минимальное кол-во треугольников в ширину
				self::$wTriaCountMax 	= 4;											// Максимальное кол-во треугольников в ширину

				self::$hTriaCountMin 	= 1;											// Минимальное кол-во треугольников в высоту
				self::$hTriaCountMax 	= 2;											// Максимальное кол-во треугольников в высоту

				self::$minQuadColor 	= 60;											// Минимальный цвет для квадратов
				self::$maxQuadColor 	= 170;											// Максимальный цвет для квадратов

				self::$minTriaColor 	= 50;											// Минимальный цвет для треугольников
				self::$maxTriaColor 	= 180;											// Максимальный цвет для треугольников

				self::$minLineColor 	= 0;											// Минимальный цвет для линий
				self::$maxLineColor 	= 255;											// Максимальный цвет для линий

				self::$lMinX			= 0;											// Минимальная координата X для линии
				self::$lMaxX 	 		= self::$captchaW;								// Максимальная координата X для линии

				self::$lMinY			= 0;											// Минимальная координата Y для линии
				self::$lMaxY 	 		= self::$captchaH;								// Максимальная координата Y для линии

				self::$tMinStartX		= 0;											// Минимальная координата X для первого символа капчи
				self::$tMaxStartX		= 3;											// Максимальная координата X для первого символа капчи

				self::$tMinStartY		= 36;											// Минимальная возможная позиция символов по Y
				self::$tMaxStartY		= 55;											// Максимальная возможная позиция символов по Y

				self::$letterSpace 		= 25;											// Расстояние между символами капчи
				self::$minAngle			= -30;											// Минимальный угол вращения для символов капчи
				self::$maxAngle			= 30;											// Максимальный угол вращения для символов капчи


				self::$textColors 		= array(										// Возможные цвета текста капчи(Важно, влияет на читабельность)
				    	'white' => array(
				     	   210,210,210
				      	  ),
				   		 'black' => array(
				     	   40,40,40
				       	  )
				);	

				break;

			case 'HARD':
				/*		
				0 - показываются случайно квадраты и треугольники
				1 - показываются только квадраты
				2 - показываются только треугольники
				*/
				self::$randFigure 		= 0;											// Установка правил для фона капчи
				self::$distort 			= true;											// Добавлять ли дисторшн на изображение(Серьезно уменьшает возможность распознавания)
				self::$fontPath  	   .= 'hard/';										// Путь к шрифтам капчи
				self::$minLength		= 4;											// Стандартная минимальная длина капчи					
				self::$maxLength		= 5;											// Стандартная максимальная длина капчи

				self::$randRegister 	= true;											// Случайный регистр для каждой буквы

				self::$minDistortX 		= 40;											// Минимальный дисторшн по X 
				self::$maxDistortX 		= 45;											// Максимальный дисторшн по X 

				self::$minDistortY 		= 40;											// Минимальный дисторшн по Y 
				self::$maxDistortY 		= 45;											// Максимальный дисторшн по Y 

				self::$fMinSize			= 17;											// Минимальный размер для шрифтов
				self::$fMaxSize			= 40;											// Максимальный размер для шрифтов

				self::$minLine 			= 3;											// Минимальное кол-во линий
				self::$maxLine 			= 5;											// Максимальное кол-во линий

				self::$wQuadCountMin 	= 3;											// Минимальное кол-во квадратов в ширину
				self::$wQuadCountMax 	= 5;											// Максимальное кол-во квадратов в ширину

				self::$hQuadCountMin 	= 1;											// Минимальное кол-во квадратов в высоту
				self::$hQuadCountMax 	= 3;											// Максимальное кол-во квадратов в высоту

				self::$wTriaCountMin 	= 3;											// Минимальное кол-во треугольников в ширину
				self::$wTriaCountMax 	= 5;											// Максимальное кол-во треугольников в ширину

				self::$hTriaCountMin 	= 2;											// Минимальное кол-во треугольников в высоту
				self::$hTriaCountMax 	= 3;											// Максимальное кол-во треугольников в высоту

				self::$minQuadColor 	= 60;											// Минимальный цвет для квадратов
				self::$maxQuadColor 	= 170;											// Максимальный цвет для квадратов

				self::$minTriaColor 	= 50;											// Минимальный цвет для треугольников
				self::$maxTriaColor 	= 180;											// Максимальный цвет для треугольников

				self::$minLineColor 	= 0;											// Минимальный цвет для линий
				self::$maxLineColor 	= 255;											// Максимальный цвет для линий

				self::$lMinX			= 0;											// Минимальная координата X для линии
				self::$lMaxX 	 		= self::$captchaW;								// Максимальная координата X для линии

				self::$lMinY			= 0;											// Минимальная координата Y для линии
				self::$lMaxY 	 		= self::$captchaH;								// Максимальная координата Y для линии

				self::$tMinStartX		= 0;											// Минимальная координата X для первого символа капчи
				self::$tMaxStartX		= 3;											// Максимальная координата X для первого символа капчи

				self::$tMinStartY		= 36;											// Минимальная возможная позиция символов по Y
				self::$tMaxStartY		= 55;											// Максимальная возможная позиция символов по Y

				self::$letterSpace 		= 25;											// Расстояние между символами капчи
				self::$minAngle			= -40;											// Минимальный угол вращения для символов капчи
				self::$maxAngle			= 40;											// Максимальный угол вращения для символов капчи


				self::$textColors 		= array(										// Возможные цвета текста капчи(Важно, влияет на читабельность)
				    	'white' => array(
				     	   210,210,210
				      	  ),
				   		 'black' => array(
				     	   40,40,40
				       	  )
				);	

				break; 

				case 'HARDCORE':
				/*		
				0 - показываются случайно квадраты и треугольники
				1 - показываются только квадраты
				2 - показываются только треугольники
				*/
				self::$randFigure 		= 0;											// Установка правил для фона капчи
				self::$distort 			= true;											// Добавлять ли дисторшн на изображение(Серьезно уменьшает возможность распознавания)
				self::$fontPath  	   .= 'hardcore/';									// Путь к шрифтам капчи
				self::$minLength		= 4;											// Стандартная минимальная длина капчи					
				self::$maxLength		= 5;											// Стандартная максимальная длина капчи

				self::$randRegister 	= true;											// Случайный регистр для каждой буквы

				self::$minDistortX 		= 45;											// Минимальный дисторшн по X 
				self::$maxDistortX 		= 50;											// Максимальный дисторшн по X 

				self::$minDistortY 		= 45;											// Минимальный дисторшн по Y 
				self::$maxDistortY 		= 50;											// Максимальный дисторшн по Y 

				self::$fMinSize			= 17;											// Минимальный размер для шрифтов
				self::$fMaxSize			= 40;											// Максимальный размер для шрифтов

				self::$minLine 			= 3;											// Минимальное кол-во линий
				self::$maxLine 			= 6;											// Максимальное кол-во линий

				self::$wQuadCountMin 	= 4;											// Минимальное кол-во квадратов в ширину
				self::$wQuadCountMax 	= 6;											// Максимальное кол-во квадратов в ширину

				self::$hQuadCountMin 	= 2;											// Минимальное кол-во квадратов в высоту
				self::$hQuadCountMax 	= 4;											// Максимальное кол-во квадратов в высоту

				self::$wTriaCountMin 	= 3;											// Минимальное кол-во треугольников в ширину
				self::$wTriaCountMax 	= 6;											// Максимальное кол-во треугольников в ширину

				self::$hTriaCountMin 	= 2;											// Минимальное кол-во треугольников в высоту
				self::$hTriaCountMax 	= 4;											// Максимальное кол-во треугольников в высоту

				self::$minQuadColor 	= 60;											// Минимальный цвет для квадратов
				self::$maxQuadColor 	= 170;											// Максимальный цвет для квадратов

				self::$minTriaColor 	= 50;											// Минимальный цвет для треугольников
				self::$maxTriaColor 	= 180;											// Максимальный цвет для треугольников

				self::$minLineColor 	= 0;											// Минимальный цвет для линий
				self::$maxLineColor 	= 255;											// Максимальный цвет для линий

				self::$lMinX			= 0;											// Минимальная координата X для линии
				self::$lMaxX 	 		= self::$captchaW;								// Максимальная координата X для линии

				self::$lMinY			= 0;											// Минимальная координата Y для линии
				self::$lMaxY 	 		= self::$captchaH;								// Максимальная координата Y для линии

				self::$tMinStartX		= 0;											// Минимальная координата X для первого символа капчи
				self::$tMaxStartX		= 3;											// Максимальная координата X для первого символа капчи

				self::$tMinStartY		= 36;											// Минимальная возможная позиция символов по Y
				self::$tMaxStartY		= 55;											// Максимальная возможная позиция символов по Y

				self::$letterSpace 		= 25;											// Расстояние между символами капчи
				self::$minAngle			= -45;											// Минимальный угол вращения для символов капчи
				self::$maxAngle			= 45;											// Максимальный угол вращения для символов капчи


				self::$textColors 		= array(										// Возможные цвета текста капчи(Важно, влияет на читабельность)
				    	'white' => array(
				     	   210,210,210
				      	  ),
				   		 'black' => array(
				     	   40,40,40
				       	  )
				);	
				break;
			default:
				# MANUAL (Берет настройки из переменных класса в объявлении)
				break;
		}
	}

	/*		Выдача случайного шрифта 		*/
	private static function getFont()
	{
		$fonts = array();
		foreach (glob(self::$fontPath . "*.ttf") as $filename)
		{
			array_push($fonts, $filename);
		}

		shuffle($fonts);
		sort($fonts);
		self::make_seed();
		return $fonts[mt_rand(0, sizeof($fonts) - 1)];
	}



	/*		Выдача случайного размера буквы		*/
	private static function getLetterSize() 
	{
		self::make_seed();
		return mt_rand(self::$fMinSize, self::$fMaxSize);
	}



	/*		Уникальный сид		*/
	private static function make_seed()
	{
	    list($usec, $sec) = explode(' ', microtime());
	    return (float) $sec + ((float) $usec * 100000);
	}



	/*		Шифрование капчи в md5 + соль		*/
	public static function encrypt($captcha)
	{
		return md5(strtolower(trim($captcha)) . self::$salt);
	}


	private static function randRegister($letter)
	{
		self::make_seed();
		if(mt_rand(0, 1))
			return strtolower($letter);
		else
			return strtoupper($letter);
	}


	/*		Генерируем капчу		*/
	public static function generate()
	{
		self::init(self::$LEVEL);
    	mt_srand(self::make_seed());
     	$length = mt_rand(self::$minLength, self::$maxLength); 															// Задаем случайную длину капчи

      	//Генерируем строку
      	for ($i = 0; $i < $length; $i++) {
      	 	mt_srand(self::make_seed());

      	 	// Если включен случайный регистр
      	 	if(self::$randRegister)
      	 		self::$captcha .= self::randRegister(self::$letterList[mt_rand(0, strlen(self::$letterList) - 1)]);		// Выбираем случайные символ из возможных с разным регистром
      	 	else
      	 		self::$captcha .= self::$letterList[mt_rand(0, strlen(self::$letterList) - 1)];							// Выбираем случайные символ из возможных
      	} 

        $array_mix = preg_split('//', self::$captcha, -1, PREG_SPLIT_NO_EMPTY);											// Разбиваем строку в массив

        // Перемешиваем
        mt_srand(self::make_seed());
        while($length--) {
        	shuffle ($array_mix);
        }
        sort($array_mix);
        self::$captcha = implode("", $array_mix);
        return self::$captcha;
	}



	/*		Возвращает случайный цвет текста		*/
	public static function getTcolor()
	{
		shuffle(self::$textColors);
		self::make_seed();
		return  self::$textColors[mt_rand(0, sizeof(self::$textColors) - 1)];
	}



	/*		Искривляет капчу		*/
	private static function makeDistort()
	{
		$rand1 = mt_rand(700000, 1000000) / 15000000;
		$rand2 = mt_rand(700000, 1000000) / 15000000;
		$rand3 = mt_rand(700000, 1000000) / 15000000;
		$rand4 = mt_rand(700000, 1000000) / 15000000;
		// фазы
		$rand5 = mt_rand(0, 3141592) / 1000000;
		$rand6 = mt_rand(0, 3141592) / 1000000;
		$rand7 = mt_rand(0, 3141592) / 1000000;
		$rand8 = mt_rand(0, 3141592) / 1000000;
		// амплитуды
		$randX = mt_rand(10, mt_rand(self::$minDistortX, self::$maxDistortX)) / 100;
		$randY = mt_rand(10, mt_rand(self::$minDistortY, self::$maxDistortY)) / 100;


        for($x = 0; $x <  self::$captchaW; $x++)
        {
            for($y = 0; $y < self::$captchaH; $y++)
            {
                // координаты пикселя-первообраза.
                $sx = $x + ( sin($x * $rand1 + $rand5) + sin($y * $rand3 + $rand6) ) * $randX;
                $sy = $y + ( sin($x * $rand2 + $rand7) + sin($y * $rand4 + $rand8) ) * $randY;

                // первообраз за пределами изображения
                if($sx < 0 || $sy < 0 || $sx >= self::$captchaW - 1 || $sy >= self::$captchaH - 1)
                {
                    $color_xy = $color_y = $color_x = $color = 255;
                }
                else
                { // цвета основного пикселя и его 3-х соседей для лучшего антиалиасинга
                    $rgb = imagecolorat(self::$img, $sx, $sy);
                    $color_r = ($rgb >> 16) & 0xFF;
                    $color_g = ($rgb >> 8) & 0xFF;
                    $color_b = $rgb & 0xFF;

                    $rgb = imagecolorat(self::$img, $sx+1, $sy);
                    $color_x_r = ($rgb >> 16) & 0xFF;
                    $color_x_g = ($rgb >> 8) & 0xFF;
                    $color_x_b = $rgb & 0xFF;

                    $rgb = imagecolorat(self::$img, $sx, $sy+1);
                    $color_y_r = ($rgb >> 16) & 0xFF;
                    $color_y_g = ($rgb >> 8) & 0xFF;
                    $color_y_b = $rgb & 0xFF;

                    $rgb = imagecolorat(self::$img, $sx+1, $sy+1);
                    $color_xy_r = ($rgb >> 16) & 0xFF;
                    $color_xy_g = ($rgb >> 8) & 0xFF;
                    $color_xy_b = $rgb & 0xFF;
                }
                // сглаживаем
                $frsx = $sx - floor($sx); //отклонение координат первообраза от целого
                $frsy = $sy - floor($sy);
                $frsx1 = 1 - $frsx;
                $frsy1 = 1 - $frsy;
                // вычисление цвета нового пикселя как пропорции от цвета основного пикселя и его соседей
                $i11 = $frsx1 * $frsy1;
                $i01 = $frsx  * $frsy1;
                $i10 = $frsx1 * $frsy ;
                $i00 = $frsx  * $frsy ;
                $red = floor(    $color_r    * $i11 +
                        $color_x_r  * $i01 +
                        $color_y_r  * $i10 +
                        $color_xy_r * $i00
                );
                $green = floor(    $color_g    * $i11 +
                        $color_x_g  * $i01 +
                        $color_y_g  * $i10 +
                        $color_xy_g * $i00
                );
                $blue = floor(    $color_b    * $i11 +
                        $color_x_b  * $i01 +
                        $color_y_b  * $i10 +
                        $color_xy_b * $i00
                );
               
            	imagesetpixel(self::$img, $x, $y, imagecolorallocate(self::$img, $red, $green, $blue));
	  		}

		}
	}


	/*		Рисуем квадраты		*/
	private static function drawQuad()
	{
		self::make_seed();
		$wQuadCount = mt_rand(self::$wQuadCountMin, self::$wQuadCountMax);                    // Сколько квадратов в ширину
		$hQuadCount = mt_rand(self::$hQuadCountMin, self::$hQuadCountMax);                    // Сколько квадратов в высоту
		$wMove = self::$captchaW / $wQuadCount;
		$hMove = self::$captchaH / $hQuadCount;

		$wStep = 0;
		$hStep = 0;

		// КВАДРАТЫ
		for($i = 0; $i < $wQuadCount; $i++) {
		    for($j = 0; $j < $hQuadCount; $j++) {
		        self::make_seed();

		        imagefilledpolygon(self::$img, Array(
		            $wStep, $hStep,
		            $wStep + $wMove, $hStep,
		            $wStep + $wMove, $hStep + $hMove,
		            $wStep, $hStep + $hMove
		            ), 4, imagecolorallocate(self::$img, mt_rand(self::$minQuadColor, self::$maxQuadColor), mt_rand(self::$minQuadColor, self::$maxQuadColor), mt_rand(self::$minQuadColor, self::$maxQuadColor) ) );

		            $hStep += $hMove;
		        }
		        $hStep = 0;
		        $wStep += $wMove;
		}

	}


	/*		Рисуем треугольники		*/
	private static function drawTria()
	{
		$wTriaCount =  mt_rand(self::$wTriaCountMin, self::$wTriaCountMax);                    // Сколько треугольников в ширину   
		$hTriaCount =  mt_rand(self::$hTriaCountMin, self::$hTriaCountMax);                   // Сколько треугольников в высоту 
		$wMove = self::$captchaW  / $wTriaCount;
		$hMove = self::$captchaH  / $hTriaCount;

		$wStep = 0;
		$hStep = 0;

		// Треугольники
		for($i = 0; $i < $wTriaCount; $i++) {
		    for($j = 0; $j < $hTriaCount; $j++) {
		        cCaptcha::make_seed();

		        imagefilledpolygon(self::$img, Array(
		            $wStep, $hStep,
		            $wStep + $wMove, $hStep + $hMove,
		            $wStep, $hStep + $hMove
		            ), 3, imagecolorallocate(self::$img, mt_rand(self::$minTriaColor, self::$maxTriaColor), mt_rand(self::$minTriaColor, self::$maxTriaColor), mt_rand(self::$minTriaColor, self::$maxTriaColor) ) );

		        cCaptcha::make_seed();
		        imagefilledpolygon(self::$img, Array(
		            $wStep, $hStep,
		            $wStep + $wMove, $hStep,
		            $wStep + $wMove, $hStep + $hMove
		            ), 3, imagecolorallocate(self::$img, mt_rand(self::$minTriaColor, self::$maxTriaColor), mt_rand(self::$minTriaColor, self::$maxTriaColor), mt_rand(self::$minTriaColor, self::$maxTriaColor) ) );

		            $hStep += $hMove;
		        }
		        $hStep = 0;
		        $wStep += $wMove;
		}

	}


	/*		Рисуем линии	*/
	private static function drawLines() 
	{
		self::make_seed();
		for ($i = 0; $i < mt_rand(self::$minLine, self::$maxLine); $i++)
        {
            $color = imagecolorallocate(self::$img, mt_rand(self::$minLineColor, self::$maxLineColor), mt_rand(self::$minLineColor, self::$maxLineColor), mt_rand(self::$minLineColor, self::$maxLineColor)); 
            imageline(self::$img, mt_rand(self::$lMinX, self::$lMaxX), mt_rand(self::$lMinX, self::$lMaxX), mt_rand(self::$lMinX, self::$lMaxX), mt_rand(self::$lMinX, self::$lMaxX), $color);
        }
	}


	/*		Проверка на правильность. Сверяются введенные данные пользователя с капчей из сессии.		*/
	public static function checkCaptcha($code)
	{
		$code = strip_tags(strtolower(trim($code)));
		if(self::encrypt($_SESSION['captcha']) == self::encrypt($code)) {
			unset($_SESSION['captcha']);
			return true;
		}
		else
			return false;
	}


	/*		Генерируем картинку капчи		*/
	public static function generateImage($code) 
	{
		if(!self::$init)
			self::init(self::$LEVEL);
		// Проверка на пустоту
		if(strlen($code) == 0 || strlen($code) < self::$minLength) {
			echo 'Длина капчи = 0 или меньше минимальной!';
			die();
		}

		// Отправляем хидары
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                   
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");         
        header("Cache-Control: post-check=0, pre-check=0", false);           
        header("Pragma: no-cache");                                           
        header("Content-Type:image/png");

		self::$img = imagecreatetruecolor(self::$captchaW, self::$captchaH);
		imagecolorallocate (self::$img, 255, 255, 255);

		self::make_seed();

		/*		Если включено показывать все или только треугольники или квадраты	*/
		if(self::$randFigure == 0) {
			if(mt_rand(0, 1))
				self::drawQuad();
			else
				self::drawTria();
		} else if(self::$randFigure == 1) {
			self::drawQuad();
		} else if(self::$randFigure == 2) {
			self::drawTria();
		}

		// Рисуем линии под текстом
		self::drawLines();


        // Получаем стартовую координату
		$x = rand(self::$tMinStartX, self::$tMaxStartX);

        for($i = 0; $i < strlen($code); $i++) {
    		// Выбираем случайный цвет для буквы
    		$tColor = self::getTcolor();
        	$color = imagecolorallocate(self::$img, $tColor[0], $tColor[1], $tColor[2]); 
            $x 		+= self::$letterSpace;                                         // Отступаем
            $letter  = $code[$i];						
            imagettftext (self::$img,											   
            			  self::getLetterSize(),								   // Случайный размер буквы
            			  rand(self::$minAngle, self::$maxAngle),				   // Случайный угол			
            			  $x,													   // Координата X
            			  rand(self::$tMinStartY, self::$tMaxStartY),			   // Случайный Y
            			  $color,												   // Случайный цвет текста
            			  self::getFont(),										   // Случайный шрифт
            			  $letter);			
        }


		// Рисуем линии над текстом
		self::drawLines();

		// Делаем дисторшн
		if(self::$distort)
			self::makeDistort();

		ImagePNG(self::$img);
        ImageDestroy (self::$img);
	}

}

?>