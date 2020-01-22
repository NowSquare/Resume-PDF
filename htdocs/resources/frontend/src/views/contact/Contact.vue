<template>
  <div class="viewContainer">
    <v-container>
      <v-row>
        <v-col
          class="ma-3"
        >
          <h1 class="display-2 mt-5 mb-10">{{ $t('contact') }}</h1>
          <div
            v-for="(paragraph, i) in $t('contact_paragraphs')"
            :key="'p_' + i"
          >
            <p
              class="headline my-5"
              v-html="paragraph"
            />
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
