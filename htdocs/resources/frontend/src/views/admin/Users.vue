<template>
  <div class="viewContainer">
    <v-container
      class="py-0"
    >
      <v-row
        no-gutters
      >
        <v-col
          cols="12"
        >
          <data-table
            model="App\User"
          >
            <template v-slot:empty-head>
              <v-icon size="72">mdi-account-multiple</v-icon>
            </template>
            <template v-slot:empty-text>
              <p class="subheading" v-html="$t('users_empty_list')"></p>
            </template>
          </data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>