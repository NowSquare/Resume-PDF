
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
            model="Platform\Models\ResumeExperience"
          >
            <template v-slot:empty-head>
              <v-icon size="72">mdi-history</v-icon>
            </template>
            <template v-slot:empty-text>
              <p class="subheading" v-html="$t('experience_empty_list')"></p>
            </template>
          </data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>