<template>
  <div class="viewContainer">
    <v-container
      class="pa-0"
    >
      <v-row
        no-gutters
        align="center"
        class="pa-5"
      >
        <v-col
          cols="12"
          sm="8"
          class="ma-0"
          :order="($vuetify.breakpoint.smAndUp) ? 1 : 2"
        >
          <h1 :class="$t('home_head_classes')" :style="$t('home_head_style')" v-html="$t('home_head')" data-aos="fade-right"></h1>
          <div :class="$t('home_head_sub_classes')" :style="$t('home_head_sub_style')" v-html="$t('home_head_sub')" data-aos="fade-left"></div>
        </v-col>
        <v-col
          cols="12"
          sm="4"
          :order="($vuetify.breakpoint.smAndDown) ? 1 : 2"
        >
          <div
            class="my-10"
          >
            <v-img :src="$t('home_head_img')" :class="$t('home_head_img_classes')" :max-width="$t('home_head_img_max_width')" :max-height="$t('home_head_img_max_height')" class="pulsate" style="margin:auto">
              <template v-slot:placeholder>
                <v-row
                  class="fill-height ma-0"
                  align="center"
                  justify="center"
                >
                  <v-overlay
                    absolute
                  >
                    <v-progress-circular indeterminate></v-progress-circular>
                  </v-overlay>
                </v-row>
              </template>
            </v-img>
          </div>
        </v-col>
      </v-row>
      <v-row
        class="pa-5"
        v-if="Array.isArray($t('home_horizontal_blocks'))"
      >
        <v-col
          v-for="(block, i) in $t('home_horizontal_blocks')"
          :key="'hb_' + i"
          :cols="$t('home_horizontal_blocks_responsiveness').xs"
          :sm="$t('home_horizontal_blocks_responsiveness').sm"
          :md="$t('home_horizontal_blocks_responsiveness').md"
          :lg="$t('home_horizontal_blocks_responsiveness').lg"
          :xl="$t('home_horizontal_blocks_responsiveness').xl"
        >
          <v-img :src="block.img" :aspect-ratio="$t('home_horizontal_blocks_aspect_ratio')" :class="$t('home_horizontal_blocks_img_classes')" v-if="block.img !== ''">
            <template v-slot:placeholder>
              <v-row
                class="fill-height ma-0"
                align="center"
                justify="center"
              >
                <v-overlay
                  absolute
                >
                  <v-progress-circular indeterminate></v-progress-circular>
                </v-overlay>
              </v-row>
            </template>
          </v-img>
          <h2 :class="$t('home_horizontal_blocks_title_classes')" data-aos="fade-up" v-html="block.title" v-if="block.title !== ''"/>
          <div :class="$t('home_horizontal_blocks_content_classes')" data-aos="fade-up" data-aos-delay="50">
            <p v-html="block.content"/>
          </div>
        </v-col>
      </v-row>
      <div v-if="Array.isArray($t('home_vertical_blocks'))">
        <v-row
          v-for="(block, i) in $t('home_vertical_blocks')"
          :key="'vb_' + i"
          class="my-10 pa-5"
        >
          <v-col
            cols="12"
            sm="6"
            :order="i%2 ?  0 : 1"
            :class="[(i%2) ? 'text-sm-right' : null]"
          >
            <h2 :class="$t('home_vertical_blocks_title_classes')" data-aos="fade-up" v-html="block.title" v-if="block.title !== ''"/>
            <div :class="$t('home_vertical_blocks_content_classes')" data-aos="fade-up" data-aos-delay="50">
              <p v-html="block.content"/>
            </div>
          </v-col>
          <v-col
            cols="12"
            sm="6"
          >
            <v-img :src="block.img" :aspect-ratio="$t('home_vertical_blocks_aspect_ratio')" :class="$t('home_vertical_blocks_img_classes')" v-if="block.img !== ''">
              <template v-slot:placeholder>
                <v-row
                  class="fill-height ma-0"
                  align="center"
                  justify="center"
                >
                  <v-overlay
                    absolute
                  >
                    <v-progress-circular indeterminate></v-progress-circular>
                  </v-overlay>
                </v-row>
              </template>
            </v-img>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </div>
</template>
<script>
export default {
  components: {
  },
  data: () => ({
  }),
  created () {
  },
  mounted () {
    document.title = this.$t('home_page_title')
  },
  methods: {
  }
};
</script>
