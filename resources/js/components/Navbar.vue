<template>
    <div>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Restaurant Manager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
              <div class="navbar-nav">
                <a class="nav-item nav-link"><router-link to="orders">Orders</router-link></a>
                <a class="nav-item nav-link"><router-link to="items">Items</router-link></a>
                <a class="nav-item nav-link" href="#">Disabled</a>
              </div>
            </div>
        </nav>
    </div>
</template>
