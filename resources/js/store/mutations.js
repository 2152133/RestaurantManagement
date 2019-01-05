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