document.addEventListener('DOMContentLoaded', function () {
    const selector = document.querySelector('select[name="inquiry_reason"]');
    console.log('Selector element: ', selector);

    const dynamicArea = document.getElementById('form-insert');

    selector.addEventListener('change', (event) => {
        const choice = event.target.value;

        if (choice === 'general') {
            dynamicArea.innerHTML = '';
        } else if (choice === 'business') {
            dynamicArea.innerHTML = provideBusinessCheckBoxes();
        } else if (choice === 'customer-service') {
            dynamicArea.innerHTML = provideCustomerServiceCheckBoxes();
        } else {
            console.log('Choice is not recognized');
        }
    });
});


function provideBusinessCheckBoxes() {
    return `
        <span>Select all that apply</span>
        <div class="form-group">
            <span class="checkbox-span">
                <input type="checkbox" name="specific_reason[]" value="sales" id="sales">
                <label for="sales">Sales</label>
            </span>
            <span class="checkbox-span">
                <input type="checkbox" name="specific_reason[]" value="marketing" id="marketing">
                <label for="marketing">Marketing</label>
            </span>
        </div>
    `;
}

function provideCustomerServiceCheckBoxes() {
    return `
        <span>Select all that apply</span>
        <div class="form-group">
            <span class="checkbox-span">
                <input type="checkbox" name="specific_reason[]" value="billing" id="billing">
                <label for="billing">Billing</label>
            </span>
            <span class="checkbox-span">
                <input type="checkbox" name="specific_reason[]" value="technical" id="technical">
                <label for="technical">Technical</label>
            </span>
            <span class="checkbox-span">
                <input type="checkbox" name="specific_reason[]" value="spoiled-product" id="spoiled-product">
                <label for="spoiled-product">Spoiled Product</label>
            </span>
            <span class="checkbox-span">
                <input type="checkbox" name="specific_reason[]" value="bill-coin-issue" id="bill-coin-issue">
                <label for="bill-coin-issue">Bill/Coin Validator Issue</label>
            </span>
            <span class="checkbox-span">
                <input type="checkbox" name="specific_reason[]" value="failed-vend" id="failed-vend">
                <label for="failed-vend">Failed to Vend</label>
            </span>
        </div>
    `;
}
