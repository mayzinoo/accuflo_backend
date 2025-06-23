<template id="tr-invoice-details">
    <tr>
        <td class="index text-center">${index}</td>
        <td class="text-center">
            ${description}
            <input type="hidden" name="item_id[]" class="item_id" value="${item_id}"/>
        </td>
        <td style="width:100px">
            <input type="text" name="quantity[]" class="form-control quantity" value="1" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
        </td>
        <td class="unit">
            <select name="unit[]" class="form-control package_id">
                ${unit}
            </select>
        </td>
        <td>
            <input type="text" name="unit_price[]" class="form-control unit_price" value="${unit_price}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
        </td>
        <td>
            <input type="text" name="extended_price[]" class="form-control extended_price" value="${extended_price}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
        </td>
        <td class="text-center">
            <i class="fa fa-trash remove" aria-hidden="true" style="font-size: 17px;line-height: 30px;color: red;cursor: pointer;"></i>
        </td>
    </tr>
</template>