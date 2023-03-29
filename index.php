<?php 
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $patronymic){
	$fullname = '';
	$fullname .= $surname;
	$fullname .= ' ';
	$fullname .= $name;
	$fullname .= ' ';
	$fullname .= $patronymic;
	return $fullname;
};

function getPartsFromFullname($person) {
	$personName = explode(' ', $person);
	$personFullname = [
		'surname' => $personName[0],
		'name' => $personName[1], 
		'patronymic' => $personName[2],
	];
	return $personFullname;
};

function getShortName($person){
	$shortname = '';
	$shortname .= getPartsFromFullname($person)['name'];
	$shortname .= ' ';
	$shortname .= mb_substr(getPartsFromFullname($person)['surname'], 0, 1);
	$shortname .= '.';
	return $shortname;
};
function getGenderFromName($person){
	$gender = 0;
	$fullname = getPartsFromFullname($person);
	$searchName = mb_substr($fullname['name'], mb_strlen($fullname['name']) - 1);
	$searchSurnameFemale = mb_substr($fullname['surname'], mb_strlen($fullname['surname']) - 2);
	$searchSurnameMale = mb_substr($fullname['surname'], mb_strlen($fullname['surname']) - 1);
	$searchPatronymicFemale = mb_substr($fullname['patronomyc'], mb_strlen($fullname['patronomyc']) - 3);
	$searchPatronymicMale = mb_substr($fullname['patronomyc'], mb_strlen($fullname['patronomyc']) - 2);
	if (($searchName == 'й' || $searchName == 'н') || ($searchSurnameMale == 'в') || ($searchPatronymicMale == 'ич')) {
		$gender++;
	}elseif (($searchName == 'а') || ($searchSurnameFemale == 'ва') || ($searchPatronymicFemale == 'вна')) {
		$gender--;
	}
	if($gender > 0){
		$printGender = 'мужчина';
	}elseif ($gender < 0) {
		$printGender = 'женщина';
	}else {
		$printGender = 'пол не определён';
	}
	return $printGender;
};
function getGenderDescription($arrayExample){
	for ($i=0; $i < count($arrayExample); $i++) { 
		$person = $arrayExample[$i]['fullname'];
		$gender[$i] = getGenderFromName($person);
		};
	$numbersMale = array_filter($gender, function($gender) {
   	return $gender == 'мужчина';
   });
	$numbersFemale = array_filter($gender, function($gender) {
   	return $gender == 'женщина';
   });
	$numbersOther = array_filter($gender, function($gender) {
   	return $gender == 'пол не определён';
	});
	$resultMale = count($numbersMale)/count($arrayExample) * 100;
	$resultFemale = count($numbersFemale)/count($arrayExample) * 100;
	$resultOther = count($numbersOther)/count($arrayExample) * 100;

	echo 'Гендерный состав аудитории: <hr>' . 'Мужчины - ' . round($resultMale, 2). '%<br>' . 'Женщины - ' . round($resultFemale, 2) . '%<br>' . 'Не удалось определить - ' . round($resultOther, 2) . '%<br>';
};

function getPerfectPartner($surname, $name, $patronymic, $arrayExample){
	$surnamePerson = mb_convert_case($surname, MB_CASE_TITLE);
	$namePerson = mb_convert_case($name, MB_CASE_TITLE);
	$patronymicPerson = mb_convert_case($patronymic, MB_CASE_TITLE); 
	$fullname = getFullnameFromParts($surnamePerson, $namePerson, $patronymicPerson);
	$genderPerson = getGenderFromName($fullname);
	$numberRand = rand(0, count($arrayExample)-1);
	$personTwo = $arrayExample[$numberRand]['fullname'];
	$genderPersonTwo = getGenderFromName($personTwo);
	if (($genderPerson == $genderPersonTwo) || ($genderPersonTwo == 'пол не определён')) {
				$genderComparison = false;
				while ($genderComparison == false) {
					if (($genderPerson != $genderPersonTwo) && ($genderPersonTwo != 'пол не определён')) {
						$genderComparison = true;
						$randomNumber = rand(5000, 10000)/100;
						$text = getShortName($fullname) . ' + ' . getShortName($personTwo) . ' = <br>' . "♡ Идеально на {$randomNumber}% ♡";
					   echo $text;
		   		};
					$numberRand = rand(0, count($arrayExample)-1);
					$personTwo = $arrayExample[$numberRand]['fullname'];
					$genderPersonTwo = getGenderFromName($personTwo);
		   	};
		}else {
			$randomNumber = rand(5000, 10000)/100;
			$text = getShortName($fullname) . ' + ' . getShortName($personTwo) . ' = <br>' . "♡ Идеально на {$randomNumber}% ♡";
		   echo $text;
		};
};

echo '<br> Разбиение и объединение ФИО.';
echo '<br> Возвращает результат склеенный через пробел. <br>';
print_r(getFullnameFromParts('Иванов', 'Иван', 'Иванович') . '<br>'); 
echo '<br> Возвращает как результат массив из трёх элементов с ключами. <br>';
print_r(getPartsFromFullname('Иванов Иван Иванович'));
echo '<br><br>Сокращение ФИО. <br>';
print_r(getShortName('Иванов Иван Иванович') . '<br>');
echo '<br> Функция определения пола по ФИО. <br>';
print_r(getGenderFromName('Иванов Иван Иванович') . '<br>');
echo '<br> Определение возрастно-полового состава. <br>';
getGenderDescription($example_persons_array);
echo '<br> Идеальный подбор пары. <br>';
getPerfectPartner('Иванов', 'ИВАН', 'иванович', $example_persons_array);
?>