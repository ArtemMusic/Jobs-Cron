<?php
/**
 * Web Application Controller
 */
namespace App\Controllers;
use Components\Request,
	App\Models\Company,
	App\Models\Office,
	App\Models\User,
	App\Models\Role;

class Tests extends \Core\Controllers\Controller
{
	private $tests = [
			'Company' => 'Company',
			'Users' => 'Users',
			'CompanyOffice' => 'Company -> Office',
			'CompanysOffices' => 'Companys -> Offices',
			'CompanyUsers' => 'Company -> Users',
			'UserCompany' => 'User -> Company',
			'UserRoles' => 'User -> Roles',
			'OpenSSL' => 'OpenSSL -> Encrypt/Decrypt',
			'Redis' => 'Redis',
			'MongoDB' => 'MongoDB'
		];
		
	/**
	 * Index page
	 */
    public function index(Request $request)
    {
		if ($request->input('run')) {
			return $this->{$request->input('run')}();
		}
		view('main.tests/index', [
			'title' => 'Tests',
			'tests' => $this->tests
		]);
	}
	
    private function Company()
    {
		$lines = [];
		$lines[]= 'Удаление всех компаний';
		Company::removeAll();
		$count = Company::getCount();
		if ($count === 0) {
			$lines[]= '<span class="text-success">Компаний: '.$count.'</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$count.'</span>';
		}
		$lines[]= 'Создание компании: Команда F5';
		$company = Company::create([
			'name' => 'Команда F5',
		]);
		if (is_object($company)) {
			$lines[]= '<span class="text-success">Создано</span>';
			$lines[]= '<span class="text-success">'.print_r($company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Не создано</span>';
		}
		$lines[]= 'Количество компаний в бд';
		$count = Company::getCount();
		if ($count === 1) {
			$lines[]= '<span class="text-success">Всего: '.$count.'</span>';
		} else {
			$lines[]= '<span class="text-danger">Всего: '.$count.'</span>';
		}
		$lines[]= 'Получение компаний';
		$companys = Company::get();
		if ($companys->count() === 1) {
			$lines[]= '<span class="text-success">Получено: '.$companys->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Получено: '.$companys->count().'</span>';
		}
		if ($company->name == 'Команда F5') {
			$lines[]= '<span class="text-success">'.print_r($company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($company->toArray(), 1).'</span>';
		}
		$lines[]= 'Переименование компании в: ООО Команда F5';
		$company->name = 'ООО Команда F5';
		$company->save();
		$lines[]= 'Получение компании по ID';
		$company = Company::find($company->id);
		if (is_object($company)) {
			$lines[]= '<span class="text-success">Получено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не получено</span>';
		}
		if ($company->name == 'ООО Команда F5') {
			$lines[]= '<span class="text-success">'.print_r($company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($company->toArray(), 1).'</span>';
		}
		$lines[]= 'Удаление компании';
		$company->remove();
		$companys = Company::get();
		if ($companys->count() == 0) {
			$lines[]= '<span class="text-success">Компаний: '.$companys->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$companys->count().'</span>';
		}
		$c1 = Company::create(['name' => 'Команда 1']);
		$c2 = Company::create(['name' => 'Команда 3']);
		$c3 = Company::create(['name' => 'Команда 2']);
		$lines[]= 'Массовое удаление компаний';
		Company::removeMany([$c1->id, $c2->id, $c3->id]);
		$companys = Company::find([$c1->id, $c2->id, $c3->id]);
		if ($companys->count() == 0) {
			$lines[]= '<span class="text-success">Успешно</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$companys->count().'</span>';
		}
		$queries = app()->getMysqlQueries();
		$lines[]= 'MySQL запросов: '.$queries->count.', за: '.round($queries->time, 5).' сек.';
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}
	
    private function Users()
    {
		$lines = [];
		$lines[]= 'Удаление всех юзеров';
		User::removeAll();
		$users = User::get();
		if ($users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$lines[]= 'Создание юзера: Жорик, test@f5.com.ru, 89001';
		$user = User::create([
			'name' => 'Жорик',
			'login' => 'test@f5.com.ru',
			'phone' => '89001',
		]);
		if (is_object($user)) {
			$lines[]= '<span class="text-success">Создано</span>';
		} else {
			$lines[]= '<span class="text-danger">Не создано</span>';
		}
		$lines[]= 'Получение юзеров';
		$users = User::get();
		if ($users->count() == 1) {
			$lines[]= '<span class="text-success">Получено: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Получено: '.$users->count().'</span>';
		}
		if ($user->name == 'Жорик') {
			$lines[]= '<span class="text-success">'.print_r($user->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($user->toArray(), 1).'</span>';
		}
		$lines[]= 'Переименование юзера в: Жора Журавлев';
		$user->name = 'Жора Журавлев';
		$user->save();
		$lines[]= 'Получение юзера по ID';
		$user = User::find($user->id);
		if (is_object($user)) {
			$lines[]= '<span class="text-success">Получено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не получено</span>';
		}
		if ($user->name == 'Жора Журавлев') {
			$lines[]= '<span class="text-success">'.print_r($user->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($user->toArray(), 1).'</span>';
		}
		$lines[]= 'Задаем пароль юзера: 1234';
		$user->setPassword(1234);
		$user->save();
		$user = User::find($user->id);
		$lines[]= 'Проверяем пароль юзера: 1234';
		if ($user->hasPassword(1234)) {
			$lines[]= '<span class="text-success">Пароль верный</span>';
		} else {
			$lines[]= '<span class="text-danger">Пароль НЕ верный</span>';
		}
		$lines[]= 'Задаем пароль юзера: ab$v%y/91x';
		$user->setPassword('ab$v%y/91x');
		$user->save();
		$user = User::find($user->id);
		$lines[]= 'Проверяем пароль юзера: ab$v%y/91x';
		if ($user->hasPassword('ab$v%y/91x')) {
			$lines[]= '<span class="text-success">Пароль верный</span>';
		} else {
			$lines[]= '<span class="text-danger">Пароль НЕ верный</span>';
		}
		$lines[]= 'Проверяем пароль юзера: 1234';
		if (!$user->hasPassword('1234')) {
			$lines[]= '<span class="text-success">Пароль НЕ верный</span>';
		} else {
			$lines[]= '<span class="text-danger">Пароль верный</span>';
		}
		$lines[]= 'Создание юзера: Егор, egor@f5.com.ru, 89001';
		$egor = User::create([
			'name' => 'Егор',
			'login' => 'egor@f5.com.ru',
			'phone' => '89001',
		]);
		if ($egor->phone == '89001') {
			$lines[]= '<span class="text-success">'.print_r($egor->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($egor->toArray(), 1).'</span>';
		}
		$lines[]= 'Получаем юзеров по телефону: 89001';
		$users = User::get([
			'phone' => 89001,
		]);
		if ($users->count() == 2) {
			$lines[]= '<span class="text-success">Получено: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Получено: '.$users->count().'</span>';
		}
		$lines[]= 'Удаление Егора';
		$egor->remove();
		$users = User::get();
		if ($users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$lines[]= 'Удаление всех юзеров';
		User::removeAll();
		$users = User::get();
		if ($users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$queries = app()->getMysqlQueries();
		$lines[]= 'MySQL запросов: '.$queries->count.', за: '.round($queries->time, 5).' сек.';
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}

    private function CompanyOffice()
    {
		$lines = [];
		$lines[]= 'Удаление всех компаний';
		Company::removeAll();
		$companys = Company::get();
		if ($companys->count() == 0) {
			$lines[]= '<span class="text-success">Компаний: '.$companys->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$companys->count().'</span>';
		}
		$lines[]= 'Удаление всех офисов';
		Office::removeAll();
		$offices = Office::get();
		if ($offices->count() == 0) {
			$lines[]= '<span class="text-success">Офисов: '.$offices->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Офисов: '.$offices->count().'</span>';
		}
		$lines[]= 'Создание компании: Команда F5';
		$company = Company::create([
			'name' => 'Команда F5',
		]);
		if (is_object($company)) {
			$lines[]= '<span class="text-success">'.print_r($company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($company->toArray(), 1).'</span>';
		}
		$lines[]= 'Проверка наличия офиса компании';
		if (!$company->office) {
			$lines[]= '<span class="text-success">Офис компании отсутствует</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании: '.$company->office->address.'</span>';
		}
		$lines[]= 'Создание офиса: Чебоксары, Карла Маркса 52';
		$che = Office::create([
			'address' => 'Чебоксары, Карла Маркса 52',
		]);
		if (is_object($che)) {
			$lines[]= '<span class="text-success">'.print_r($che->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Не создано</span>';
		}
		$lines[]= 'Прикрепление офиса к компании';
		$company->office()->attach($che);
		if ($company->office) {
			$lines[]= '<span class="text-success">'.print_r($company->office->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании отсутствует</span>';
		}
		$lines[]= 'Проверка офиса на наличие компании';
		if ($che->company && $che->company->id == $company->id) {
			$lines[]= '<span class="text-success">'.print_r($che->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($che->toArray(), 1).'</span>';
		}
		$lines[]= 'Создание офиса: Москва, Казанская 15';
		$mos = Office::create([
			'address' => 'Москва, Казанская 15',
		]);
		if (is_object($mos)) {
			$lines[]= '<span class="text-success">'.print_r($mos->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($mos->toArray(), 1).'</span>';
		}
		$lines[]= 'Прикрепление офиса к компании';
		$company->office()->attach($mos);
		if ($company->office->id == $mos->id) {
			$lines[]= '<span class="text-success">Офис компании: '.$company->office->address.'</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании: '.$company->office->address.'</span>';
		}
		$lines[]= 'Проверка офиса на наличие компании';
		if (is_object($mos->company) && $mos->company->id == $company->id) {
			$lines[]= '<span class="text-success">'.print_r($mos->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($mos->toArray(), 1).'</span>';
		}
		$lines[]= 'Открепление офиса от компании';
		$company->office()->detach($mos);
		if (!$company->office) {
			$lines[]= '<span class="text-success">Офис компании отсутствует</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании: '.$company->office->address.'</span>';
		}
		$lines[]= 'Проверка офиса на наличие компании';
		if (is_object($mos->company) && $mos->company->id == $mos->id) {
			$lines[]= '<span class="text-danger">'.print_r($mos->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-success">'.print_r($mos->toArray(), 1).'</span>';
		}
		$lines[]= 'Создание офиса через компанию: Казань, Набережная 3';
		$kaz = $company->office()->create([
			'address' => 'Казань, Набережная 3'
		]);
		if (is_object($kaz)) {
			$lines[]= '<span class="text-success">'.print_r($kaz->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($kaz->toArray(), 1).'</span>';
		}
		$lines[]= 'Проверка офиса на наличие компании';
		if (is_object($kaz->company) && $kaz->company->id == $company->id) {
			$lines[]= '<span class="text-success">'.print_r($kaz->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($kaz->toArray(), 1).'</span>';
		}
		$lines[]= 'Обновление офиса компании: Сочи, Шишкина 7';
		$company->office->update([
			'address' => 'Сочи, Шишкина 7',
		]);
		if ($company->office->address == 'Сочи, Шишкина 7') {
			$lines[]= '<span class="text-success">Офис компании: '.$company->office->address.'</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании: '.$company->office->address.'</span>';
		}
		$lines[]= 'Удаление офиса компании с очисткой кеша связи';
		$company->office->remove();
		$company->office()->clearCache();
		if (!$company->office) {
			$lines[]= '<span class="text-success">Офис компании отсутствует</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании: '.$company->office->address.'</span>';
		}
		$lines[]= 'Удаление компании';
		$company_id = $company->id;
		$company->remove();
		$company = Company::find($company_id);
		if (!is_object($company)) {
			$lines[]= '<span class="text-success">Удалена</span>';
		} else {
			$lines[]= '<span class="text-danger">Не удалена</span>';
		}
		$che->remove();
		$mos->remove();
		$queries = app()->getMysqlQueries();
		$lines[]= 'MySQL запросов: '.$queries->count.', за: '.round($queries->time, 5).' сек.';
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}

	private function CompanysOffices()
    {
		$lines = [];
		$lines[]= 'Удаление всех компаний';
		Company::removeAll();
		$companys = Company::get();
		if ($companys->count() == 0) {
			$lines[]= '<span class="text-success">Компаний: '.$companys->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$companys->count().'</span>';
		}
		$lines[]= 'Удаление всех офисов';
		Office::removeAll();
		$offices = Office::get();
		if ($offices->count() == 0) {
			$lines[]= '<span class="text-success">Офисов: '.$offices->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Офисов: '.$offices->count().'</span>';
		}
		$lines[]= 'Создание компаний: Команда F5, Щавельсервис';
		$comf5 = Company::create([
			'name' => 'Команда F5',
		]);
		$shavel = Company::create([
			'name' => 'Щавельсервис',
		]);
		if (is_object($comf5) && is_object($shavel)) {
			$lines[]= '<span class="text-success">'.print_r($comf5->toArray(), 1).print_r($shavel->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Не создано</span>';
		}
		$lines[]= 'Проверка наличия офисов компаний';
		if ($comf5->offices->count() == 0 && $shavel->offices->count() == 0) {
			$lines[]= '<span class="text-success">Офисы компаний отсутствуют</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании: '.print_r($comf5->offices->toArray(), 1).print_r($shavel->offices->toArray(), 1).'</span>';
		}
		$lines[]= 'Создание офисов: Чебоксары, Карла Маркса 52; Казань, Ленина 79; Москва, Пушкина 4';
		$che = Office::create([
			'address' => 'Чебоксары, Карла Маркса 52',
		]);
		$kaz = Office::create([
			'address' => 'Казань, Ленина 79',
		]);
		$mos = Office::create([
			'address' => 'Москва, Пушкина 4',
		]);
		if (is_object($che) && is_object($kaz) && is_object($mos)) {
			$lines[]= '<span class="text-success">'.print_r($che->toArray(), 1).print_r($kaz->toArray(), 1).print_r($mos->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Не создано</span>';
		}
		$lines[]= 'Проверка наличия компаний у офисов';
		if ($che->companys->count() == 0 && $kaz->companys->count() == 0 && $mos->companys->count() == 0) {
			$lines[]= '<span class="text-success">Компании офисов отсутствуют</span>';
		} else {
			$lines[]= '<span class="text-danger">Офис компании: '.print_r($che->companys->toArray(), 1).print_r($kaz->companys->toArray(), 1).print_r($mos->companys->toArray(), 1).'</span>';
		}
		$lines[]= 'Прикрепление офисаов Чебоксары и Казань к компании Команда F5';
		$comf5->offices()->attach($che, ['is_main' => 1]);
		$comf5->offices()->attach($kaz);
		if ($comf5->offices->count() == 2 && $comf5->offices()->find($che->id)->linked->is_main == 1) {
			$lines[]= '<span class="text-success">Прикреплены</span>';
		} else {
			$lines[]= '<span class="text-danger">Не прикреплены</span>';
		}
		$lines[]= 'Прикрепление офисаов Казань и Москва к компании Щавельсервис';
		$shavel->offices()->attach($kaz, ['is_main' => 1]);
		$shavel->offices()->attach($mos);
		if ($shavel->offices->count() == 2 && $shavel->offices()->find($kaz->id)->linked->is_main == 1) {
			$lines[]= '<span class="text-success">Прикреплены</span>';
		} else {
			$lines[]= '<span class="text-danger">Не прикреплены</span>';
		}
		$lines[]= 'Проверка офисов на наличие компаний';
		if ($che->companys->count() == 1 && $che->companys->first()->id == $comf5->id) {
			$lines[]= '<span class="text-success">Чебкосары - Команда F5</span>';
		} else {
			$lines[]= '<span class="text-danger">Не соответствует</span>';
		}
		if ($kaz->companys->count() == 2 && $kaz->companys->first()->id == $comf5->id && $kaz->companys->last()->linked->is_main == 1 && $kaz->companys->last()->id == $shavel->id) {
			$lines[]= '<span class="text-success">Казань - Команда F5, Щавельсервис</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($kaz->companys->toArray(), 1).'</span>';
		}
		if ($mos->companys->count() == 1 && $mos->companys->first()->id == $shavel->id) {
			$lines[]= '<span class="text-success">Москва - Щавельсервис</span>';
		} else {
			$lines[]= '<span class="text-danger">Не соответствует</span>';
		}
		$lines[]= 'Проверка компаний на наличие офисов';
		if ($comf5->offices->count() == 2 && $comf5->offices->first()->id == $che->id && $comf5->offices->last()->id == $kaz->id) {
			$lines[]= '<span class="text-success">Команда F5 - Чебкосары, Казань</span>';
		} else {
			$lines[]= '<span class="text-danger">Не соответствует</span>';
		}
		if ($shavel->offices->count() == 2 && $shavel->offices->first()->id == $kaz->id && $shavel->offices->last()->id == $mos->id) {
			$lines[]= '<span class="text-success">Щавельсервис - Казань, Москва</span>';
		} else {
			$lines[]= '<span class="text-danger">Не соответствует</span>';
		}
		$lines[]= 'Поменяем основной офис Команды F5 через связь Компания->Офисы';
		$comf5->offices->first()->linked->update(['is_main' => 0]);
		$comf5->offices->last()->linked->update(['is_main' => 1]);
		if ($comf5->offices()->find($che->id)->linked->is_main == 0 && $comf5->offices()->find($kaz->id)->linked->is_main == 1) {
			$lines[]= '<span class="text-success">Выполнено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не выполнено</span>';
		}
		$lines[]= 'Поменяем основной офис Щавельсервиса через связь Офис->Компании';
		$kaz->companys->find('id', $shavel->id)->first()->linked->update(['is_main' => 0]);
		$mos->companys->find('id', $shavel->id)->first()->linked->update(['is_main' => 1]);
		if ($shavel->offices()->find($kaz->id)->linked->is_main == 0 && $shavel->offices()->find($mos->id)->linked->is_main == 1) {
			$lines[]= '<span class="text-success">Выполнено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не выполнено</span>';
		}
		$lines[]= 'Создадим офис Щавельсервиса в Омске через связь Компания->Офисы';
		$omsk = $shavel->offices()->create(['address' => 'Омск, Гагарина 10'], ['is_main' => 3]);
		if ($shavel->offices->count() == 3 && $shavel->offices()->find($omsk->id)->linked->is_main == 3) {
			$lines[]= '<span class="text-success">Выполнено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не выполнено</span>';
		}
		$lines[]= 'Создадим компанию Фокус в Казани через связь Офис->Компании';
		$focus = $kaz->companys()->create(['name' => 'Фокус'], ['is_main' => 5]);
		if ($kaz->companys->count() == 3 && $kaz->companys()->get(['company_id' => $focus->id])->first()->linked->is_main == 5) {
			$lines[]= '<span class="text-success">Выполнено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не выполнено</span>';
		}
		$lines[]= 'Открепим офис Щавельсервиса в Казани через связь Компания->Офисы';
		$shavel->offices()->detach($kaz);
		if ($kaz->companys->count() == 2 && $shavel->offices->count() == 2) {
			$lines[]= '<span class="text-success">Выполнено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не выполнено</span>';
		}
		$lines[]= 'Прикрепим Команде F5 офис в Москве через связь Офис->Компании';
		$comf5->offices()->attach($mos);
		if ($mos->companys->count() == 2 && $comf5->offices->count() == 3) {
			$lines[]= '<span class="text-success">Выполнено</span>';
		} else {
			$lines[]= '<span class="text-danger">Не выполнено</span>';
		}
		$comf5->remove();
		$shavel->remove();
		$focus->remove();
		
		$che->remove();
		$kaz->remove();
		$mos->remove();
		$omsk->remove();
		
		$queries = app()->getMysqlQueries();
		$lines[]= 'MySQL запросов: '.$queries->count.', за: '.round($queries->time, 5).' сек.';
		$lines[]= 'Конец';
		
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}
	
    private function CompanyUsers()
    {
		$lines = [];
		$lines[]= 'Удаление всех компаний';
		Company::removeAll();
		$companys = Company::get();
		if ($companys->count() == 0) {
			$lines[]= '<span class="text-success">Компаний: '.$companys->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$companys->count().'</span>';
		}
		$lines[]= 'Удаление всех юзеров';
		User::removeAll();
		$users = User::get();
		if ($users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$lines[]= 'Создание компании: Команда F5';
		$company = Company::create([
			'name' => 'Команда F5',
		]);
		if (is_object($company)) {
			$lines[]= '<span class="text-success">'.print_r($company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($company->toArray(), 1).'</span>';
		}
		$lines[]= 'Проверка кол-ва юзеров компании';
		if ($company->users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Создание юзера: Жорик, test@f5.com.ru';
		$jora = User::create([
			'name' => 'Жорик',
			'login' => 'test@f5.com.ru',
		]);
		if (is_object($jora)) {
			$lines[]= '<span class="text-success">'.print_r($jora->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($jora->toArray(), 1).'</span>';
		}
		$lines[]= 'Прикрепление Жорика к компании';
		$company->users()->attach($jora);
		if ($company->users->count() === 1) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
			$lines[]= '<span class="text-success">'.print_r($company->users->first()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Проверка Жорика на наличие компании';
		if (is_object($jora->company) && $jora->company->id == $company->id) {
			$lines[]= '<span class="text-success">'.print_r($jora->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($jora->toArray(), 1).'</span>';
		}
		$lines[]= 'Создание юзера: Василий, vass@f5.com.ru';
		$vass = User::create([
			'name' => 'Василий',
			'login' => 'vass@f5.com.ru',
		]);
		if (is_object($vass)) {
			$lines[]= '<span class="text-success">'.print_r($vass->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($vass->toArray(), 1).'</span>';
		}
		$lines[]= 'Прикрепление Василия к компании';
		$company->users()->attach($vass);
		if ($company->users->count() == 2) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
			foreach ($company->users as $user) {
				$lines[]= '<span class="text-info">Юзер: '.$user->name.'</span>';
			}
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Проверка Василия на наличие компании';
		if (is_object($vass->company) && $vass->company->id == $company->id) {
			$lines[]= '<span class="text-success">'.print_r($vass->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($vass->toArray(), 1).'</span>';
		}
		$lines[]= 'Открепление Василия от компании';
		$company->users()->detach($vass);
		if ($company->users->count() === 1) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
			$lines[]= '<span class="text-success">'.print_r($company->users->last()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Проверка Василия на наличие компании';
		if (is_object($vass->company) && $vass->company->id == $company->id) {
			$lines[]= '<span class="text-danger">'.print_r($vass->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-success">'.print_r($vass->toArray(), 1).'</span>';
			$vass->remove();
		}
		$lines[]= 'Создание юзера через компанию: Егор, egor@f5.com.ru';
		$egor = $company->users()->create([
			'name' => 'Егор',
			'login' => 'egor@f5.com.ru',
		]);
		if (is_object($egor)) {
			$lines[]= '<span class="text-success">'.print_r($egor->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($egor->toArray(), 1).'</span>';
		}
		$lines[]= 'Проверка кол-ва юзеров компании';
		if ($company->users->count() == 2) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
			$lines[]= '<span class="text-success">'.print_r($company->users->last()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Проверка Егора на наличие компании';
		if (is_object($egor->company) && $egor->company->id == $company->id) {
			$lines[]= '<span class="text-success">'.print_r($egor->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($egor->toArray(), 1).'</span>';
		}
		$lines[]= 'Удаление Егора через компанию';
		$company->users()->remove($egor);
		if ($company->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Обновление юзеров через компанию';
		$company->users()->update([
			'phone' => '89002000600',
		]);
		if ($company->users->first()->phone == '89002000600') {
			$lines[]= '<span class="text-success">'.print_r($company->users->first()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($company->users->first()->toArray(), 1).'</span>';
		}
		$lines[]= 'Удаление юзеров компании';
		$company->users()->remove();
		if ($company->users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Удаление компании';
		$company_id = $company->id;
		$company->remove();
		$company = Company::find($company_id);
		if (!is_object($company)) {
			$lines[]= '<span class="text-success">Удалена</span>';
		} else {
			$lines[]= '<span class="text-danger">Не удалена</span>';
		}
		$queries = app()->getMysqlQueries();
		$lines[]= 'MySQL запросов: '.$queries->count.', за: '.round($queries->time, 5).' сек.';
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}
	
    private function UserCompany()
    {
		$lines = [];
		$lines[]= 'Удаление всех юзеров';
		User::removeAll();
		$users = User::get();
		if ($users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$lines[]= 'Удаление всех компаний';
		Company::removeAll();
		$companys = Company::get();
		if ($companys->count() == 0) {
			$lines[]= '<span class="text-success">Компаний: '.$companys->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$companys->count().'</span>';
		}
		$lines[]= 'Создание юзера: Жорик, test@f5.com.ru';
		$jora = User::create([
			'name' => 'Жорик',
			'login' => 'test@f5.com.ru',
		]);
		if (is_object($jora)) {
			$lines[]= '<span class="text-success">'.print_r($jora->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($jora->toArray(), 1).'</span>';
		}
		$lines[]= 'Компания Жорика';
		if (!is_object($jora->company)) {
			$lines[]= '<span class="text-success">Нет</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($jora->company->toArray(), 1).'</span>';
		}
		$lines[]= 'Создание компании: Команда F5';
		$company = Company::create([
			'name' => 'Команда F5',
		]);
		if (is_object($company)) {
			$lines[]= '<span class="text-success">'.print_r($company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($company->toArray(), 1).'</span>';
		}
		if ($company->users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Прикрепление Жорика к компании: Команда F5';
		$jora->company()->attach($company);
		if ($company->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Компания Жорика';
		if (is_object($jora->company)) {
			$lines[]= '<span class="text-success">'.print_r($jora->company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Нет</span>';
		}
		$lines[]= 'Создание юзера через компанию: Егор, egor@f5.com.ru';
		$egor = $company->users()->create([
			'name' => 'Егор',
			'login' => 'egor@f5.com.ru',
		]);
		if (is_object($egor)) {
			$lines[]= '<span class="text-success">'.print_r($egor->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.print_r($egor->toArray(), 1).'</span>';
		}
		$lines[]= 'Проверка кол-ва юзеров компании';
		if ($company->users->count() == 2) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Открепление Егора от компании: Команда F5';
		$egor->company()->detach();
		if ($company->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$company->users->count().'</span>';
			$lines[]= '<span class="text-success">'.print_r($company->users->first()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$company->users->count().'</span>';
		}
		$lines[]= 'Компания Егора';
		if (!is_object($egor->company) && is_null($egor->company_id)) {
			$lines[]= '<span class="text-success">Нет</span>';
		} else {
			$lines[]= '<span class="text-danger">Да '.print_r($egor->company->toArray(), 1).'</span>';
		}
		$lines[]= 'Компания Жорика';
		if (is_object($jora->company)) {
			$lines[]= '<span class="text-success">Да '.print_r($jora->company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Нет</span>';
		}
		$lines[]= 'Создание компании через Егора: Агро';
		$agro = $egor->company()->create([
			'name' => 'Агро',
		]);
		$lines[]= 'Компания Егора';
		if (is_object($egor->company) && $egor->company->name == 'Агро') {
			$lines[]= '<span class="text-success">'.print_r($egor->company->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">'.$egor->company_id.': '.print_r($egor->company->toArray(), 1).'</span>';
			exit;
		}
		$lines[]= 'Проверка кол-ва юзеров компании Агро';
		if ($egor->company->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров компании: '.$egor->company->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров компании: '.$egor->company->users->count().'</span>';
		}
		$lines[]= 'Удаление компании Команда F5 через Жорика';
		$company_id = $company->id;
		$jora->company()->remove();
		$lines[]= 'Компания Жорика';
		if (!is_object($jora->company)) {
			$lines[]= '<span class="text-success">Нет</span>';
		} else {
			$lines[]= '<span class="text-danger">Да '.print_r($jora->company->toArray(), 1).'</span>';
		}
		$company = Company::find($company_id);
		if (!is_object($company)) {
			$lines[]= '<span class="text-success">Компания удалена</span>';
		} else {
			$lines[]= '<span class="text-danger">Компания НЕ удалена</span>';
		}
		$lines[]= 'Удаление компании Агро';
		$egor->company->remove();
		$companys = Company::get();
		if ($companys->count() == 0) {
			$lines[]= '<span class="text-success">Компаний: '.$companys->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Компаний: '.$companys->count().'</span>';
		}
		$lines[]= 'Удаление всех юзеров';
		User::removeAll();
		$users = User::get();
		if ($users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$queries = app()->getMysqlQueries();
		$lines[]= 'MySQL запросов: '.$queries->count.', за: '.round($queries->time, 5).' сек.';
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}
	
    private function UserRoles()
    {
		$lines = [];
		$lines[]= 'Удаление всех ролей';
		Role::removeAll();
		$roles = Role::get();
		if ($roles->count() == 0) {
			$lines[]= '<span class="text-success">Ролей: '.$roles->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ролей: '.$roles->count().'</span>';
		}
		$lines[]= 'Удаление всех юзеров';
		User::removeAll();
		$users = User::get();
		if ($users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$lines[]= 'Создание ролей: Администратор, Модератор';
		$roles = Role::insert([
			['name' => 'Администратор', 'code' => 'admin'],
			['name' => 'Модератор', 'code' => 'moder']
		]);
		if ($roles->count() == 2) {
			$lines[]= '<span class="text-success">Ролей: '.$roles->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ролей: '.$roles->count().'</span>';
		}
		$lines[]= 'Создание юзеров: Егор, Василий';
		$users = User::insert([
			['name' => 'Егор', 'login' => 'egor'],
			['name' => 'Василий', 'login' => 'vass']
		]);
		if ($users->count() == 2) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$admin = Role::find($roles->find('code', 'admin')->first()->id);
		$lines[]= 'Связанные юзеры роли: Администратор';
		if ($admin->users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$admin->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$admin->users->count().'</span>';
		}
		$moder = Role::find($roles->find('code', 'moder')->first()->id);
		$lines[]= 'Связанные юзеры роли: Модератор';
		if ($moder->users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$moder->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$moder->users->count().'</span>';
		}
		$egor = User::find($users->find('login', 'egor')->first()->id);
		$lines[]= 'Связанные роли юзера: Егор';
		if ($egor->roles->count() == 0) {
			$lines[]= '<span class="text-success">Ролей: '.$egor->roles->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ролей: '.$egor->roles->count().'</span>';
		}
		$vass = User::find($users->find('login', 'vass')->first()->id);
		$lines[]= 'Связанные роли юзера: Василий';
		if ($vass->roles->count() == 0) {
			$lines[]= '<span class="text-success">Ролей: '.$vass->roles->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ролей: '.$vass->roles->count().'</span>';
		}
		$lines[]= 'Присвоим Егору роль Администратор';
		$egor->roles()->attach($admin);
		if ($egor->hasRole('admin')) {
			$lines[]= '<span class="text-success">Егор Администратор</span>';
			$lines[]= '<span class="text-success">'.print_r($egor->roles->first()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Егор НЕ Администратор</span>';
		}
		$lines[]= 'Присвоим Василию роль Модератор';
		$vass->roles()->attach($moder);
		if ($vass->hasRole('moder')) {
			$lines[]= '<span class="text-success">Василий Модератор</span>';
			$lines[]= '<span class="text-success">'.print_r($vass->roles->first()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Василий НЕ Модератор</span>';
		}
		$lines[]= 'Связанные юзеры роли: Администратор';
		if ($admin->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров: '.$admin->users->count().'</span>';
			$lines[]= '<span class="text-success">'.print_r($admin->users->first()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$admin->users->count().'</span>';
		}
		$moder = Role::find($roles->find('code', 'moder')->first()->id);
		$lines[]= 'Связанные юзеры роли: Модератор';
		if ($moder->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров: '.$moder->users->count().'</span>';
			$lines[]= '<span class="text-success">'.print_r($moder->users->first()->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$moder->users->count().'</span>';
		}
		$lines[]= 'Присвоим Администраторам юзера Василий';
		$admin->users()->attach($vass);
		if ($vass->hasRole('admin')) {
			$lines[]= '<span class="text-success">Василий Администратор</span>';
		} else {
			$lines[]= '<span class="text-danger">Василий НЕ Администратор</span>';
		}
		$lines[]= 'Связанные роли юзера: Василий';
		if ($vass->roles->count() == 2) {
			$lines[]= '<span class="text-success">Ролей: '.$vass->roles->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ролей: '.$vass->roles->count().'</span>';
		}
		$lines[]= 'Связанные юзеры роли: Администратор';
		if ($admin->users->count() == 2) {
			$lines[]= '<span class="text-success">Юзеров: '.$admin->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$admin->users->count().'</span>';
		}
		$lines[]= 'Уберем у Василия роль Администратора';
		$vass->roles()->detach($admin);
		if ($vass->hasRole('admin')) {
			$lines[]= '<span class="text-danger">Василий Администратор</span>';
		} else {
			$lines[]= '<span class="text-success">Василий НЕ Администратор</span>';
		}
		$lines[]= 'Связанные юзеры роли: Администратор';
		if ($admin->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров: '.$admin->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$admin->users->count().'</span>';
		}
		$lines[]= 'Уберем из Администраторов юзера Егор';
		$admin->users()->detach($egor);
		$lines[]= 'Связанные юзеры роли: Администратор';
		if ($admin->users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$admin->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$admin->users->count().'</span>';
		}
		if ($egor->hasRole('admin')) {
			$lines[]= '<span class="text-danger">Егор Администратор</span>';
		} else {
			$lines[]= '<span class="text-success">Егор НЕ Администратор</span>';
		}
		$lines[]= 'Создадим юзера в роли Администратор: Жора';
		$jora = $admin->users()->create([
			'name' => 'Жора', 'login' => 'jora'
		]);
		if (is_object($jora) && $jora->login == 'jora') {
			$lines[]= '<span class="text-success">Создан</span>';
			$lines[]= '<span class="text-success">'.print_r($jora->toArray(), 1).'</span>';
		} else {
			$lines[]= '<span class="text-danger">НЕ создан</span>';
		}
		if ($jora->hasRole('admin')) {
			$lines[]= '<span class="text-success">Жора Администратор</span>';
		} else {
			$lines[]= '<span class="text-danger">Жора НЕ Администратор</span>';
		}
		$lines[]= 'Связанные юзеры роли: Администратор';
		if ($admin->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров: '.$admin->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$admin->users->count().'</span>';
		}
		$lines[]= 'Связанные роли юзера: Егор';
		if ($egor->roles->count() == 0) {
			$lines[]= '<span class="text-success">Ролей: '.$egor->roles->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ролей: '.$egor->roles->count().'</span>';
		}
		$lines[]= 'Создадим роль Гость через Егора';
		$guest = $egor->roles()->create([
			'name' => 'Гость', 'code' => 'guest'
		]);
		if ($egor->hasRole('guest')) {
			$lines[]= '<span class="text-success">Егор Гость</span>';
		} else {
			$lines[]= '<span class="text-danger">Егора Гость</span>';
		}
		$lines[]= 'Связанные юзеры роли: Гость';
		if ($guest->users->count() == 1) {
			$lines[]= '<span class="text-success">Юзеров: '.$guest->users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$guest->users->count().'</span>';
		}
		$lines[]= 'Удаление всех ролей';
		Role::removeAll();
		$roles = Role::get();
		if ($roles->count() == 0) {
			$lines[]= '<span class="text-success">Ролей: '.$roles->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ролей: '.$roles->count().'</span>';
		}
		$lines[]= 'Удаление всех юзеров';
		User::removeAll();
		$users = User::get();
		if ($users->count() == 0) {
			$lines[]= '<span class="text-success">Юзеров: '.$users->count().'</span>';
		} else {
			$lines[]= '<span class="text-danger">Юзеров: '.$users->count().'</span>';
		}
		$queries = app()->getMysqlQueries();
		$lines[]= 'MySQL запросов: '.$queries->count.', за: '.round($queries->time, 5).' сек.';
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}
	
	/**
	 * Test 
	 */
    public function test()
    {
		$user = User::find(1);
		$user->save();
		print_r($user);
		var_dump($user->hasPassword('1234'));
		
		exit;
		echo "\n\n-- Resource: --\n\n";
		
		print_r($user->roles);
		
		echo "\n\n-- Wrapper: --\n\n";
		
		print_r($user->roles());
		
		echo "\n\n-- Action: --\n\n";
		
		$role = Role::find(4);
		print_r($role->users);
		
		$role->users()->attach($user);

		echo "\n\n-- Result: --\n\n";
		
		print_r($role->users);
		print_r($user->roles);
		
		
	}
	
	/**
	 * Test belongsToMany
	 */
    public function belongsToManyTest()
    {
		$role = Role::find(3);
		echo "\n\n-- Role: --\n\n";
		print_r($role);
		
		echo "\n\n-- Role Users --\n\n";
		print_r($role->users->all());
		
		echo "\n\n-- Role Users2 --\n\n";
		print_r($role->users->all());
		
		$role->users->detach();
		
		echo "\n\n-- Role Users detached --\n\n";
		print_r($role->users->all());
		
		$role->users->attach(2);
		
		echo "\n\n-- Role Users attached 2 --\n\n";
		print_r($role->users->all());
		
		echo "\n\n-- Role Users --\n\n";
		print_r($role->users->all());
		
		$role->users->attach([1,3]);
		
		echo "\n\n-- Role Users attached 1,3 --\n\n";
		print_r($role->users->all());
		
		
		echo "\n\n--  --\n\n";
	}
	
	/**
	 * Test hasMany
	 */
    public function hasManyTest()
    {
		$user = User::find(3);
		echo "\n\n-- User: --\n\n";
		print_r($user);
		
		echo "\n\n-- User Roles --\n\n";
		print_r($user->roles->all());
		
		echo "\n\n-- User Roles2 --\n\n";
		print_r($user->roles->all());
		
		$user->roles->detach();
		
		echo "\n\n-- User Roles detached --\n\n";
		print_r($user->roles->all());
		
		$user->roles->attach(2);
		
		echo "\n\n-- User Roles attached 2 --\n\n";
		print_r($user->roles->all());
		
		echo "\n\n-- User Roles --\n\n";
		print_r($user->roles->all());
		
		$user->roles->attach([1,3]);
		
		echo "\n\n-- User Roles attached 1,3 --\n\n";
		print_r($user->roles->all());
		
		echo "\n\n--  --\n\n";
	}
	
    private function OpenSSL()
    {
		$vars = [
			' ',
			123,
			123.456,
			156754567869325,
			'test |',
			'test | ',
			' test |',
			' test /',
			' test \\',
			' test //',
			' test %',
			' test % ',
			' test % =',
			' test %/==,',
			' test %/==.',
			'/ /',
			'=%',
			'"',
			' test " ::',
			' test " ] * # $ ! @ ^ & ?',
			' Ge%20a cD0m+o fH/ig+P Y0==',
			'-=+'
		];
		$lines = [];
		foreach ($vars as $var) {
			$lines[]= 'Значение: "'.$var."\"\n";
			$encoded = encrypt($var);
			$decoded = decrypt($encoded);
			if ($decoded === $var) {
				$lines[]= '<span class="text-success">'.$encoded.' -> "'.$decoded.'"</span>';
			} else {
				$lines[]= '<span class="text-success">'.$encoded.' -> "'.$decoded.'"</span>';
			}
		}
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines,
		]);
	}
	
    private function Redis()
    {
		$lines = [];
		$redis = app('redis');
		$time = time();
		
		if ($redis->connection()->ping()) {
			$lines[]= '<span class="text-success">Redis доступен</span>';
		} else {
			$lines[]= '<span class="text-danger">Redis НЕ доступен</span>';
		}
		$redis->set('testmvc', $time);
		$val = $redis->get('testmvc');
		if ($val === $time) {
			$lines[]= '<span class="text-success">Ключ установлен</span>';
			$lines[]= '<span class="text-success">'.$val.'</span>';
		} else {
			$lines[]= '<span class="text-danger">Ключ НЕ установлен</span>';
		}
		$redis->del('testmvc');
		$val = $redis->get('testmvc');
		if (!$val) {
			$lines[]= '<span class="text-success">Ключ удален</span>';
		} else {
			$lines[]= '<span class="text-danger">Ключ НЕ удален</span>';
		}
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines
		]);
	}
	
    private function MongoDB()
    {
		$lines = [];
		$cnf = config('mongo.default');
		try {
			$client = app('mongo')->makeClient();
			$database = $client->selectDatabase($cnf['authdb']);
			$database->command(['ping' => 1]);
			$lines[]= '<span class="text-success">Mongo доступен</span>';
		} catch(\Throwable $e) {
			$lines[]= '<span class="text-danger">Mongo НЕ доступен: '.$e->getMessage().'</span>';
		}
		$lines[]= 'Конец';
		view('main.tests/test', [
			'title' => $this->request->input('run'),
			'lines' => $lines
		]);
	}
}
