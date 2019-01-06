export default {
    getToken(state) {
        let token = localStorage.getItem('access_token')
        let tokenType = localStorage.getItem('token_type')
        let expiration = localStorage.getItem('expiration_time')

        if (!token || !tokenType || !expiration) {
            return null;
        }

        if (Date.now() > parseInt(expiration)) {
            state.token = "";
            state.tokenType = "";
            localStorage.removeItem('token_type')
            localStorage.removeItem('access_token')
            localStorage.removeItem('expiration_time')
            axios.defaults.headers.common.Authorization = undefined;
            return null;
        }

        return token;
    },
    getTokenType(state) {
        let tokenType = localStorage.getItem('token_type')
        
        if (!tokenType) {
            return null;
        }

        return tokenType;
    },
    getExpiration(state) {
        let expiration = localStorage.getItem('expiration_time')
        
        if (!expiration) {
            return null;
        }

        return expiration;
    },
    getAuthUser(state) {
        let user = localStorage.getItem('user')

        if (!user) {
            return null;
        }

        return JSON.parse(user);
    },
    isAuthenticated(state) {
        return state.token && state.tokenType ? true:false;
    },
    isManager(state) {
        if(state.user)
            return state.user.type == "manager" ? true : false;
        return false    
    },

    isCook(state) {
        if(state.user)
            return state.user.type == "cook" ? true : false;
        return false    
    },
    isCashier(state) {
        if(state.user)
            return state.user.type == "cashier" ? true : false;
        return false    
    },
    isWaiter(state) {
        if(state.user)
            return state.user.type == "waiter" ? true : false;
        return false    
    },

//-----------------Orders-----------------------
    confirmedOrders(state){
        return state.confirmedOrders;
    },
    confirmedOrdersMeta(state){
        return state.confirmedOrdersMeta;
    },
    confirmedOrdersLinks(state){
        return state.confirmedOrdersLinks;
    },
    inPreparationUserOrders(state){
        return state.inPreparationUserOrders;
    },
    inPreparationUserOrdersMeta(state){
        return state.inPreparationUserOrdersMeta;
    },
    inPreparationUserOrdersLinks(state){
        return state.inPreparationUserOrdersLinks;
    },
    currentOrder(state){
        return state.currentOrder;
    },


    //-------------------------Meals------------------------
    userMeals(state){
        return state.userMeals;
    },
    userMealsMeta(state){
        return state.userMealsMeta;
    },
    userMealsLinks(state){
        return state.userMealsLinks;
    },
    currentMeal(state){
        return state.currentMeal;
    },
    allMealOrders(state){
        return state.allMealOrders;
    },
    pendingMealOrders(state){
        return state.pendingMealOrders;
    },
    confirmedMealOrders(state){
        return state.confirmedMealOrders;
    },
    preparedMealOrders(state){
        return state.preparedMealOrders;
    },
    notDeliveredOrdersOfMeal(state){
        return state.notDeliveredOrdersOfMeal;
    },
    allItems(state){
        return state.allItems;
    },
    mealDetails(state){
        return state.mealDetails;
    },
    currentMealOrder(state){
        return state.currentMealOrder;
    },
    counter(state){
        return state.counter;
    },


    //-----------------------Invoices----------------------
    currentInvoice(state){
        return state.currentInvoice;
    },
    pendingInvoices(state){
        return state.pendingInvoices;
    },
    pendingInvoicesMeta(state){
        return state.pendingInvoicesMeta;
    },
    pendingInvoicesLinks(state){
        return state.pendingInvoicesLinks;
    },
    paidInvoices(state){
        return state.paidInvoices;
    },
    paidInvoicesMeta(state){
        return state.paidInvoicesMeta;
    },
    paidInvoicesLinks(state){
        return state.paidInvoicesLinks;
    },
    editingNifName(state){
        return state.editingNifName;
    },
    viewingDetails(state){
        return state.viewingDetails;
    },


    //---------------------Management----------------------
    tables(state){
        return state.tables;
    },
    currentTable(state){
        return state.currentTable;
    },
    tablesMeta(state){
        return state.tablesMeta;
    },
    tablesLinks(state){
        return state.tablesLinks;
    },
    editingTable(state){
        return state.editingTable;
    },
    creatingTable(state){
        return state.creatingTable;
    },
    currentItem(state){
        return state.currentItem;
    },
    items(state){
        return state.items;
    },
}