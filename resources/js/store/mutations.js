export default {
    setToken(state, {token, tokenType, expiration}) {
        state.token = token;
        state.tokenType = tokenType;
        state.expiration = expiration;
        localStorage.setItem('token_type', tokenType);
        localStorage.setItem('access_token', token);
        localStorage.setItem('expiration_time', expiration);
        axios.defaults.headers.common.Authorization = "Bearer " + token;
    },
    clearToken(state) {
        state.token = "";
        state.expiration = 0;
        localStorage.removeItem('token_type')
        localStorage.removeItem('access_token')
        localStorage.removeItem('expiration_time')
        axios.defaults.headers.common.Authorization = undefined;
    },
    setAuthUser(state, user) {
        state.user =  user;
        localStorage.setItem('user', JSON.stringify(user));
    },
    clearUser(state) {
        state.user = null;
        localStorage.removeItem('user');
    },
    clearUserAndToken(state) {
        state.user = null;
        state.token = "";
        state.expiration = 0;
        localStorage.removeItem('user');
        localStorage.removeItem('token_type')
        localStorage.removeItem('access_token');
        localStorage.removeItem('expiration_time')
        axios.defaults.headers.common.Authorization = undefined;
    },


    //-----------orders--------------------
    assignResponseToInPreparationUserOrders(state, response){
        state.inPreparationUserOrders = response.data.data;
        state.inPreparationUserOrdersMeta = response.data.meta;
        state.inPreparationUserOrdersLinks = response.data.links;
    },
    assignResponseToConfirmedOrders(state, response){
        state.confirmedOrders = response.data.data;
        state.confirmedOrdersMeta = response.data.meta;
        state.confirmedOrdersLinks = response.data.links;
    },
    cleanOrdersArrays(state){
        state.confirmedOrders = [];
        state.inPreparationUserOrders = [];
    },
    refreshConfirmedOrders(state, payload){
        state.confirmedOrders = payload.newConfirmedOrders;
        state.confirmedOrdersMeta = payload.newConfirmedMeta;
        state.confirmedOrdersLinks = payload.newConfirmedLinks;
    },
    refreshInPreparationUserOrders(state, payload){
        state.inPreparationUserOrders = payload.newInPreparationUserOrders;
        state.inPreparationUserOrdersMeta = payload.newInPreparationUserOrdersMeta;
        state.inPreparationUserOrdersLinks = payload.newInPreparationUserOrdersLinks;
    },
    refreshTablesPagination(state, response){
        state.tables = response.data.data;
        state.tablesMeta = response.data.meta;
        state.tablesLinks = response.data.links;
    },

    //-----------------------Invoices-------------------------------------------
    refreshPendingInvoices(state, payload){
        state.pendingInvoices = payload.newPendingInvoices;
        state.pendingInvoicesMeta = payload.newMeta;
        state.pendingInvoicesLinks =  payload.newLinks;
    },
    refreshPaidInvoices(state, payload){
        state.paidInvoices = payload.newPaidInvoices;
        state.paidInvoicesMeta = payload.newMeta;
        state.paidInvoicesLinks = payload.newLinks;
    },

    //-----------------------Items----------------------------------
    refreshItems(state, payload) {
        state.items = payload.newItems;
        state.itemsMeta = payload.newMeta;
        state.itemsLinks = payload.newLinks;
    },

    //--------------------Items setters-------------------------------
    setItems(state, items){
        state.items = items;
    },
    setItemsMeta(state, itemsMeta){
        state.itemsMeta = itemsMeta;
    },
    setItemsLinks(state, itemsLinks){
        state.itemsLinks = itemsLinks;
    },

    //-----------------------Meals----------------------------------
    removeMealFromUserMeals(state, index){
        state.userMeals.splice(index, 1);
    },
    addOrderToPendingMealOrders(state, order){
        state.pendingMealOrders.push(order);
    },
    removeOrderFromPendingMealOrders(state, index){
        state.pendingMealOrders.splice(index, 1);
    },
    removeOrderFromPreparedMealOrders(state, index){
        state.preparedMealOrders.splice(index, 1);
    },
    createNewPendingMeal(state, payload){
        state.currentMealOrder.id = state.counter;
        state.currentMealOrder.state = "pending";
        state.currentMealOrder.item = payload.item;
        state.currentMealOrder.responsible_cook = null;
        state.currentMealOrder.meal_id = payload.mealId;
        state.currentMealOrder.start = payload.datetimeToOrder;
    },

    //------------------Setters---------------------------------------------------------------------------
    //--------------------Order setters-------------------------------
    setConfirmedOrders(state, confirmedOrders){
        state.confirmedOrders = confirmedOrders;
    },
    setConfirmedOrdersMeta(state, confirmedOrdersMeta){
        state.confirmedOrdersMeta = confirmedOrdersMeta;
    },
    setConfirmedOrdersLinks(state, confirmedOrdersLinks){
        state.confirmedOrdersLinks = confirmedOrdersLinks;
    },
    setInPreparationUserOrders(state, inPreparationUserOrders){
        state.inPreparationUserOrders = inPreparationUserOrders;
    },
    setInPreparationUserOrdersMeta(state, inPreparationUserOrdersMeta){
        state.inPreparationUserOrdersMeta = inPreparationUserOrdersMeta;
    },
    setInPreparationUserOrdersLinks(state, inPreparationUserOrdersLinks){
        state.inPreparationUserOrdersLinks = inPreparationUserOrdersLinks;
    },
    setCurrentOrder(state, order){
        state.currentOrder = order;
    },


    

    //-------------------------Meal setters-----------------------------------
    setUserMeals(state, userMeals){
        state.userMeals = userMeals;
    },
    setCurrentMeal(state, currentMeal){
        state.currentMeal = currentMeal;
    },
    setAllMealOrders(state, allMealOrders){
        state.allMealOrders = allMealOrders;
    },
    setPendingMealOrders(state, pendingMealOrders){
        state.pendingMealOrders = pendingMealOrders;
    },
    setConfirmedMealOrders(state, confirmedMealOrders){
        state.confirmedMealOrders = confirmedMealOrders;
    },
    setPreparedMealOrders(state, preparedMealOrders){
        state.preparedMealOrders = preparedMealOrders;
    },
    setNotDeliveredOrdersOfMeal(state, notDeliveredOrdersOfMeal){
        state.notDeliveredOrdersOfMeal = notDeliveredOrdersOfMeal;
    },
    setAllItems(state, allItems){
        state.allItems = allItems;
    },
    setMealDetails(state, mealDetails){
        state.mealDetails = mealDetails;
    },
    setCurrentMealOrder(state, currentMealOrder){
        state.currentMealOrder = currentMealOrder;
    },
    setCounter(state, counter){
        state.counter = counter;
    },




    //----------------------Invoice Setters-------------------------------------
    setCurrentInvoice(state, invoice){
        state.currentInvoice = invoice;
    },
    setPendingInvoices(state, pendingInvoices){
        state.pendingInvoices = pendingInvoices;
    },
    setPendingInvoicesMeta(state, pendingInvoicesMeta){
        state.pendingInvoicesMeta = pendingInvoicesMeta;
    },
    setPendingInvoicesLinks(state, pendingInvoicesLinks){
        state.pendingInvoicesLinks = pendingInvoicesLinks;
    },
    setPaidInvoices(state, paidInvoices){
        state.paidInvoices = paidInvoices;
    },
    setPaidInvoicesMeta(state, paidInvoicesMeta){
        state.paidInvoicesMeta = paidInvoicesMeta;
    },
    setPaidInvoicesLinks(state, paidInvoicesLinks){
        state.paidInvoicesLinks = paidInvoicesLinks;
    },




    //--------------------Table setters-----------------------------------------
    setTables(state, tables){
        state.tables = tables;
    },
    setCurrentTable(state, table){
        state.currentTable = table;
    },
    setTablesMeta(state, tablesMeta){
        state.tablesMeta = tablesMeta;
    },
    setTablesLinks(state, tablesLinks){
        state.tablesLinks = tablesLinks;
    },
    setEditingTable(state, table){
        state.editingTable = table;
    },
    setCreatingTable(state, table){
        state.creatingTable = table;
    },
    setCurrentItem(state, item){
        state.currentItem = item;
    },
    setItems(state, items){
        state.items = items;
    },
}