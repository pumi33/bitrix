<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");



use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

class Brands
{

    public $block_id = 1;

    public function __construct()
    {
        CModule::IncludeModule('highloadblock');
    }


    /** Вспомогательный класс
     * @param $HlBlockId
     * @return bool
     */
    private function GetEntityDataClass($HlBlockId)
    {
        if (empty($HlBlockId) || $HlBlockId < 1) {
            return false;
        }
        $hlblock = HLBT::getById($HlBlockId)->fetch();
        $entity = HLBT::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    /** Таблица
     * @param null $country
     * @return string
     */
    public function drowTable($country = null)
    {

        $filter = [];
        if (isset($country))
            $filter = ['filter' => ['UF_CODE_COUNTRY' => $country]];


        $entity_data_class = $this->GetEntityDataClass($this->block_id);
        $rsData = $entity_data_class::getList($filter);


        $data = "<table class='table' border=1 cellpadding='20'><tr><th>Страна</th><th>Бренд</th><th>Редактировать / Удалить</th></tr>";
        while ($el = $rsData->fetch()) {

            $data .= "<tr><td><a href='?country=" . $el['UF_CODE_COUNTRY'] . "'>" . $el['UF_CODE_COUNTRY'] . "</td>";
            $data .= "<td>" . $el['UF_BRAND'] . "</td>";

            $data .= "<td align=center>
<a href=\"?edit=" . $el['ID'] . "\"><img src=\"http://www.forum.noorclinic.com/images/posticons/11.gif\"></a>
<a href=\"?del=" . $el['ID'] . "\"><img src=\"https://hspolicy.debatecoaches.org/resources/icons/silk/delete.png\"></a>
</td></tr>";


        }

        $data .= "<form method='POST'><tr align=center>
<td><input type='text' name='country' placeholder='Страна' maxlenght='3'></td>
<td><input type='text' name='brand' placeholder='Бренд' maxlenght='10'></td>
<td><input type='submit' name='add' value='Добавить запись'></td>
</tr></form>";

        $data .= "</table>";


        return $data;
    }


    /** Редактивароние
     * @param $id
     * @return string
     */
    public function drowEditTable($id)
    {
        if (!empty($_POST['country']) and !empty($_POST['brand']) and !empty($_POST['edit'])) {

            $entity_data_class = $this->GetEntityDataClass($this->block_id);
            $result = $entity_data_class::update($id, [
                'UF_CODE_COUNTRY' => $_POST['country'],
                'UF_BRAND' => $_POST['brand']
            ]);
        }


        $data = "Edit";
        $filter = ['filter' => ['ID' => $id]];


        $entity_data_class = $this->GetEntityDataClass($this->block_id);
        $rsData = $entity_data_class::getList($filter);
        $el = $rsData->fetch();


        $data .= "<table><form method='POST'><tr align=center>
<td><input type='text' name='country' value='" . $el['UF_CODE_COUNTRY'] . "' maxlenght='3'></td>
<td><input type='text' name='brand' value='" . $el['UF_BRAND'] . "' maxlenght='10'></td>
<td><input type='submit' name='edit' value='Редактировать запись'></td>
</tr></form></table>";

        return $data;

    }

    /** Удаление записи
     * @param $id
     */
    public function deleteRow($id)
    {
        $idForDelete = (int)$id;
        $entity_data_class = $this->GetEntityDataClass($this->block_id);
        $result = $entity_data_class::delete($idForDelete);
    }


    /** Добавление записи
     * @param $country
     * @param $brand
     */
    public function addRow($country, $brand)
    {
        $entity_data_class = $this->GetEntityDataClass($this->block_id);
        $result = $entity_data_class::add([
            'UF_CODE_COUNTRY' => $country,
            'UF_BRAND' => $brand
        ]);
    }


}


$brands = new Brands();


if (is_numeric($_GET['del']))
    $brands->deleteRow($_GET['del']);

if (!empty($_POST['country']) and !empty($_POST['brand'] and !empty($_POST['add'])))
    $brands->addRow($_POST['country'], $_POST['brand']);


if (is_numeric($_GET['edit'])) {
    print $brands->drowEditTable($_GET['edit']);
} else {
    print $brands->drowTable($_GET['country']);
}


?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>