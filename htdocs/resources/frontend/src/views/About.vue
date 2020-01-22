<template>
  <div class="viewContainer">
    <v-container
      :fluid="true"
    >
      <v-row
        no-gutters
      >
        <v-img :src="$t('about_head_img')" :aspect-ratio="$t('about_head_img_aspect_ratio')" @load="showHeaderImageContent = true" :max-height="$t('about_head_img_max_height')" :class="$t('about_head_img_classes')">
          <template v-slot:placeholder>
            <v-row
              class="fill-height ma-0"
              align="center"
              justify="center"
            >
              <v-overlay
                absolute
                :opacity="$t('about_head_img_overlay_opacity')"
                :color="$t('about_head_img_overlay_color')"
              >
                <v-progress-circular indeterminate :color="page.header.textColor + ' ' + page.header.textColorBrightness"></v-progress-circular>
              </v-overlay>
            </v-row>
          </template>
          <template v-slot:default v-if="showHeaderImageContent">
            <v-row 
              no-gutters
              align="center"
              justify="center"
              class="fill-height"
            >
              <v-overlay
                absolute
                :opacity="$t('about_head_img_overlay_opacity')"
                :color="$t('about_head_img_overlay_color')"
                class="pa-5"
              >
                <h1 :class="$t('about_head_classes')" :style="$t('about_head_style')" v-html="$t('about_head')" data-aos="fade-right"></h1>
                <div :class="$t('about_head_sub_classes')" :style="$t('about_head_sub_style')" v-html="$t('about_head_sub')" data-aos="fade-left"></div>
              </v-overlay>
            </v-row>
          </template>
        </v-img>
      </v-row>
    </v-container>
    <v-container>
      <v-row
        class="pa-5"
        v-if="Array.isArray($t('about_horizontal_blocks'))"
      >
        <v-col
          v-for="(block, i) in $t('about_horizontal_blocks')"
          :key="'hb_' + i"
          :cols="$t('about_horizontal_blocks_responsiveness').xs"
          :sm="$t('about_horizontal_blocks_responsiveness').sm"
          :md="$t('about_horizontal_blocks_responsiveness').md"
          :lg="$t('about_horizontal_blocks_responsiveness').lg"
          :xl="$t('about_horizontal_blocks_responsiveness').xl"
        >
          <v-img :src="block.img" :aspect-ratio="$t('about_horizontal_blocks_aspect_ratio')" :class="$t('about_horizontal_blocks_img_classes')" v-if="block.img !== ''">
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
          <h2 :class="$t('about_horizontal_blocks_title_classes')" data-aos="fade-up" v-html="block.title" v-if="block.title !== ''"/>
          <div :class="$t('about_horizontal_blocks_content_classes')" data-aos="fade-up" data-aos-delay="50">
            <p v-html="block.content"/>
          </div>
        </v-col>
      </v-row>
      <div v-if="Array.isArray($t('about_vertical_blocks'))">
        <v-row
          v-for="(block, i) in $t('about_vertical_blocks')"
          :key="'vb_' + i"
          class="my-10 pa-5"
        >
          <v-col
            cols="12"
            sm="6"
            :order="i%2 ?  0 : 1"
            :class="[(i%2) ? 'text-sm-right' : null]"
          >
            <h2 :class="$t('about_vertical_blocks_title_classes')" data-aos="fade-up" v-html="block.title" v-if="block.title !== ''"/>
            <div :class="$t('about_vertical_blocks_content_classes')" data-aos="fade-up" data-aos-delay="50">
              <p v-html="block.content"/>
            </div>
          </v-col>
          <v-col
            cols="12"
            sm="6"
          >
            <v-img :src="block.img" :aspect-ratio="$t('about_vertical_blocks_aspect_ratio')" :class="$t('about_vertical_blocks_img_classes')" v-if="block.img !== ''">
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
    showHeaderImageContent: false,
    content: null,
    contentSubscription: null,
    page: {
      header: {
        bgImg: 'https://picsum.photos/510/300?random',
        aspectRatio: 1.7,
        maxHeight: 480,
        overlayColor: '#000000',
        overlayOpacity: 0.8,
        textColor: 'grey',
        textColorBrightness: 'lighten-2',
        title: 'BrandAwareness',
        subtitle: 'ScriptTitle brought to you for free by BusinessName'
      }
    },
    services: {

    },
    stories: {

    },
    products: {

    }
  }),
  mounted () {
    document.title = this.$t('about_page_title')
  },
  methods: {
  }
};
</script>
