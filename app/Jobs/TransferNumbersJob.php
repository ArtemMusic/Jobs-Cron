<?php

namespace App\Jobs;

class TransferNumbersJob extends \Core\Jobs\QueueJob
{
    const timeFileLeads = "/home/tests/web/tests.cmdf5.ru/public_html/dev6/resources/cron/cron_helper_contact.txt";

    //Точка входа переноса слитых номеров
    public function handle()
    {
        //Проходимся по всем контактам
        $amo = app('client')->crmIntegration();
        $lastCronTimeFileLead = file_get_contents(self::timeFileLeads);

        //берем контакты из списка контактов через крон
        $contacts = $amo->contacts()
            ->modifiedFrom($lastCronTimeFileLead)
            ->maxRows(100)
            ->listing();

        //берем телефоны контактов(как они есть) и удаляем их, предварительно сохраним в переменную
        $contacts->each(function ($contact) {
            $l = logger('transfer.log');
            $l->log('id = ',$contact);
            $lastCronTimeFileLead = file_put_contents(self::timeFileLeads,($contact->updated_at));
//            $notFilteredNumbers = $contact->cf('Телефон')->values;
//            $contact->cf('Телефон')->reset();
//            $contact->save();
//
//            //Создаем массив для хранения измененных номеров
//            $changedNumbers = [];
//
//            //Проходимся по каждому номеру
//            foreach ($notFilteredNumbers as $notFilteredNumber) {
//                $number = str_replace(' ', '', trim($notFilteredNumber->value));
//                $number = str_replace('+7', '8', $number);
//                $changedNumbers[] = $number;
//            }
//
//            //Проходимся по каждому подготовленному номеру и переносим в отдельные ячейки слитые номера
//            $transferredNumbers = self::filterNumbers($changedNumbers);
//            if (empty($transferredNumbers)) {
//                $transferredNumbers = $notFilteredNumbers;
//            }
//
//            //Меняем 89... на +79...
//            foreach ($transferredNumbers as $transferredNumber) {
//                $transferredNumber[0] = '7';
//                $transferredNumber = '+' . $transferredNumber;
//                $contact->cf('Телефон')->setValue($transferredNumber);
//            }
//
//            //Сохраняем контакт
//            $contact->save();
        });
    }

    //Фильтрует номера и переносит слитые на отдельные ячейки
    private static function filterNumbers($filteredNumbers)
    {
        //Проходимся по не перенесенным номерам
        for ($i = 0; $i <= count($filteredNumbers); $i++) {
            //Если номер больше 12 символов => номера слиты и их нужно разъединить
            if (isset($filteredNumbers[$i][12])) {
                $number = substr($filteredNumbers[$i], 0, 11);
                $filteredNumbers[$i] = substr($filteredNumbers[$i], 11);
                $filteredNumbers[] = $number;
            }
            //Пройдет еще раз, если номера слиты больше чем 2 номера в строке
            if (isset($filteredNumbers[$i][12])) {
                $filteredNumbers = self::filterNumbers($filteredNumbers);
            }
        }
        return $filteredNumbers;
    }
}