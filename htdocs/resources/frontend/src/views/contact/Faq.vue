<template>
  <div class="viewContainer">
    <v-container>
      <v-row>
        <v-col
          class="ma-3"
        >
          <h1 class="display-2 mt-5 mb-10">{{ $t('faq') }}</h1>
          <div
            v-for="(paragraphs, title) in $t('faq_paragraphs')"
            :key="'p_' + title"
          >
            <h2
              class="headline my-5"
              v-html="title"
            />
            <div
              v-for="(p, title) in paragraphs"
              :key="'p_' + title"
            >
              <p
                v-if="!Array.isArray(p)"
                class="subtitle-1"
                v-html="p"
              />
              <ol
                v-else-if="Array.isArray(p)"
                class="my-5"
              >
                <li
                  v-for="(li, i) in p"
                  :key="'li_' + i"
                  v-html="li"
                />
              </ol>
            </div>

          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
