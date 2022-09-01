<template>
  <v-card 
    class="pa-6"
    width="100%"
    max-width="1024"
    :disabled="busy"
    rounded="xl"
  >
    <v-card-title class="text-h4">
      Список объявлений
    </v-card-title>
    <v-row dense>
      <v-col cols="12">
        <v-data-table
          :headers="tableHeaders"
          :items="list"
          :loading="busy"
          :options.sync="tableOptions"
          :server-items-length="total"
          hide-default-footer
          sort-desc
        >
          <template #header.actions>
            <v-row no-gutters>
              <v-col
                cols="auto"
              >
                <v-btn
                  icon
                  @click="createItem"
                >
                  <v-icon>
                    mdi-plus
                  </v-icon>
                </v-btn>
              </v-col>
              <v-col
                cols="auto"
              >
                <v-btn
                  icon
                  @click="refreshList"
                >
                  <v-icon>
                    mdi-refresh
                  </v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </template>
          <template #item.main_photo="{ item }">
            <v-img
              class="my-3 rounded-lg"
              content-class="d-flex align-center justify-center"
              :src="item.main_photo"
              aspect-ratio="1.4"
            >
              <div
                v-if="!item.main_photo"
                class="text-caption text--secondary"
              >
                нет фото
              </div>
            </v-img>
          </template>
          <template #item.price="{ item }">
            {{ getFormattedPrice(item) }}
          </template>
          <template #item.actions="{ item }">
            <v-row no-gutters>
              <v-col cols="auto">
                <v-btn
                  icon
                  @click="updateItem(item)"
                >
                  <v-icon>
                    mdi-pencil-outline
                  </v-icon>
                </v-btn>
              </v-col>
              <v-col cols="auto">
                <v-menu
                  offset-x
                  bottom
                  right
                >
                  <template #activator="{ on }">
                    <v-btn
                      color="error"
                      icon
                      v-on="on"
                    >
                      <v-icon>
                        mdi-trash-can-outline
                      </v-icon>
                    </v-btn>
                  </template>
                  <v-card class="pl-3">
                    Правда удалить?
                    <v-btn
                      color="error"
                      text
                      @click="deleteItem(item)"
                    >
                      АГА
                    </v-btn>
                  </v-card>
                </v-menu>
              </v-col>
            </v-row>
          </template>
        </v-data-table>
      </v-col>
      <v-col
        v-show="list.length"
        cols="12"
      >
        <v-pagination
          v-model="tableOptions.page"
          :length="tablePageCount"
        />
      </v-col>
    </v-row>
    <v-dialog
      :value="editor.show"
      max-width="700"
      persistent
    >
      <vee-observer
        ref="formObserverRef"
        v-slot="{ errors, handleSubmit }"
        slim
      >
        <v-form
          autocomplete="off"
          :readonly="dialogBusy"
          @submit.prevent="handleSubmit(submitEditorDialog)"
        >
          <v-card
            :loading="dialogBusy"
            flat
          >
            <v-card-title>
              Объявление
            </v-card-title>
            <v-card-text>
              <v-row>
                <v-col cols="12">
                  <vee-provider
                    vid="name"
                    name=" "
                    rules="required|max:200"
                  >
                    <v-text-field
                      label="Названиие"
                      v-model="editor.item.name"
                      :error-messages="errors.name"
                      hide-details="auto"
                      counter="200"
                    />
                  </vee-provider>
                </v-col>
                <v-col cols="12">
                  <vee-provider
                    vid="description"
                    name=" "
                    rules="required|max:1000"
                  >
                    <v-textarea
                      label="Описание"
                      v-model="editor.item.description"
                      :error-messages="errors.description"
                      hide-details="auto"
                      counter="1000"
                      auto-grow
                      rows="1"
                    />
                  </vee-provider>
                </v-col>
                <v-col cols="4">
                  <vee-provider
                    vid="price"
                    name=" "
                    rules="required"
                  >
                    <v-text-field
                      label="Цена"
                      v-model.number="editor.item.price"
                      :error-messages="errors.price"
                      hide-details="auto"
                      hide-spin-buttons
                      type="number"
                    />
                  </vee-provider>
                </v-col>
                <v-col
                  class="d-flex align-center text-body-1 mt-4"
                  cols="12"
                >
                  Фото ({{ editor.item.photos.length }}/3)
                  <vee-provider
                    vid="photos"
                    name=" "
                  >
                    <input
                      v-model="editor.item.photos"
                      type="hidden"
                    >
                  </vee-provider>
                  <v-btn
                    class="ml-auto"
                    color="primary"
                    :disabled="editor.item.photos.length >= 3"
                    outlined
                    small
                    @click="addPhoto"
                  >
                    <v-icon left>
                      mdi-plus
                    </v-icon>
                    Добавить фото
                  </v-btn>
                </v-col>
                <v-col
                  v-if="errors.photos && errors.photos.length"
                  class="error--text"
                  cols="12"
                >
                  {{ errors.photos.join('; ') }}
                </v-col>
                <v-col cols="12">
                  <v-list>
                    <template v-for="photo, photoIndex in editor.item.photos">
                      <v-divider />
                      <v-list-item
                        :key="`pht_${photoIndex}`"
                      >
                        <v-list-item-action>
                          <v-badge
                            :value="photoIndex === 0"
                            content="главное"
                            bordered
                            overlap
                          >
                            <v-img
                              class="rounded-lg"
                              :src="photo"
                              aspect-ratio="1.4"
                              width="80"
                            />
                          </v-badge>
                        </v-list-item-action>
                        <v-list-item-content>
                          <vee-provider
                            :vid="`photos.${photoIndex}`"
                            name=" "
                            rules="required"
                          >
                            <v-text-field
                              v-model="editor.item.photos[photoIndex]"
                              :error-messages="errors[`photos.${photoIndex}`]"
                              hide-details="auto"
                              solo-inverted
                              dense
                              flat
                            />
                          </vee-provider>
                        </v-list-item-content>
                        <v-list-item-action>
                          <v-row no-gutters>
                            <v-col cols="auto">
                              <v-divider class="mr-2" vertical />
                            </v-col>
                            <v-col cols="auto">
                              <v-btn 
                                icon
                                @click="sortPhoto(photo)"
                              >
                                <v-icon>
                                  mdi-chevron-up
                                </v-icon>
                              </v-btn>
                            </v-col>
                            <v-col cols="auto">
                              <v-btn 
                                icon
                                @click="sortPhoto(photo, false)"
                              >
                                <v-icon>
                                  mdi-chevron-down
                                </v-icon>
                              </v-btn>
                            </v-col>
                            <v-col cols="auto">
                              <v-btn
                                color="error"
                                icon
                                @click="deletePhoto(photo)"
                              >
                                <v-icon>
                                  mdi-trash-can-outline
                                </v-icon>
                              </v-btn>
                            </v-col>
                          </v-row>
                        </v-list-item-action>
                      </v-list-item>
                    </template>
                  </v-list>
                </v-col>
              </v-row>
            </v-card-text>
            <v-divider />
            <v-card-actions>
              <v-spacer />
              <v-btn
                color="error"
                text
                @click="hideEditorDialog"
              >
                Отмена
              </v-btn>
              <v-btn 
                color="primary"
                type="submit"
                text
              >
                Сохранить
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-form>
      </vee-observer>
    </v-dialog>
  </v-card>
</template>

<script>
  import axios from '@/plugins/axios';

  export default {
    name: 'AdList',
    data: () => ({
      tableOptions: {},
      tablePageCount: 0,
      list: [],
      total: 0,
      editor: {
        show: false,
        item: {
          id: null,
          name: '',
          description: '',
          price: '',
          photos: [],
        },
      },
      busy: false,
      dialogBusy: false,
    }),
    computed: {
      tableHeaders () {
        return [
          {
            text: 'ID',
            value: 'id',
            width: '7%',
            sortable: false,
          },
          {
            text: 'Фото',
            value: 'main_photo',
            width: '10%',
            sortable: false,
          },
          {
            text: 'Название',
            value: 'name',
            sortable: false,
          },
          {
            text: 'Цена',
            value: 'price',
            width: '15%',
          },
          {
            text: 'Создано',
            value: 'created_at',
            width: '15%',
          },
          {
            value: 'actions',
            width: '11%',
            sortable: false,
          },
        ];
      },
    },
    watch: {
      tableOptions: {
        handler () {
          this.fetchList()
        },
        deep: true,
      },
    },
    methods: {
      async fetchList () {
        this.busy = true;

        const tableOptions = this.tableOptions;

        const params = {
          page: tableOptions.page,
        };

        if (tableOptions.sortBy.length) {
          params.sortBy = tableOptions.sortBy[0];
        }

        if (tableOptions.sortDesc.length) {
          params.sortDir = tableOptions.sortDesc[0] ? 'desc' : 'asc';
        }

        const ads = (await axios.get('ads', {
          params,
        })).data;

        this.list = ads.data;
        this.total = ads.meta.total;

        this.tablePageCount = ads.meta.last_page;

        this.busy = false;
      },
      refreshList () {
        this.tableOptions.page = 1;

        this.fetchList();
      },
      createItem () {
        this.showEditorDialog();
      },
      async updateItem ({ id }) {
        try {
          this.busy = true;
          
          const item = (await axios.get(`ads/${id}`, {
            params: {
              fields: ['description', 'photos'],
            }
          })).data.data;
          
          this.busy = false;
          
          this.showEditorDialog(item);
        } catch (error) {
          // ...
        }
      },
      async deleteItem (item) {
        const targetItemIndex = this.findItemIndex(item);

        if (targetItemIndex === -1) {
          return;
        }

        this.busy = true;

        await axios.delete(`ads/${item.id}`);

        this.list.splice(targetItemIndex, 1);

        this.busy = false;
      },
      showEditorDialog (item = null) {
        this.editor.show = true;
  
        if (!item) {
          return;
        }
          
        this.editor.item = {
          id: item.id,
          name: item.name,
          description: item.description,
          price: item.price,
          photos: item.photos,
        };
      },
      hideEditorDialog () {
        this.editor.show = false;

        this.editor.item = {
          id: null,
          name: '',
          description: '',
          price: '',
          photos: [],
        };
      },
      async submitEditorDialog () {
        const item = this.editor.item;
        const isNew = !item.id;

        const submitUri = `ads${!isNew ? `/${item.id}` : ''}`;
        const submitMethod = isNew ? 'post' : 'patch';

        this.dialogBusy = true;

        try {
          const result = (await axios[submitMethod](submitUri, item)).data.data;

          if (isNew) {
            this.list.unshift(result);
          } else {
            const targetItemIndex = this.findItemIndex(item);

            if (targetItemIndex !== -1) {
              const targetItem = this.list[targetItemIndex];

              targetItem.name = result.name;
              targetItem.price = result.price;
              targetItem.main_photo = result.main_photo;
            }
          }

          this.hideEditorDialog();
        } catch ({ response }) {
          if (response.status === 422) {
            this.$refs.formObserverRef.setErrors(response.data.errors);
          }
        }

        this.dialogBusy = false;
      },
      findItemIndex ({ id }) {
        return this.list.findIndex(item => item.id === id);
      },
      findPhotoIndex (photo) {
        return this.editor.item.photos.findIndex(photoItem => photoItem === photo);
      },
      addPhoto () {
        const link = window.prompt('Укажите ссылку...');

        if (!link) {
          return;
        }

        this.editor.item.photos.push(link);
      },
      sortPhoto (photo, up = true) {
        const photoIndex = this.findPhotoIndex(photo);

        if (photoIndex === -1) {
          return;
        }

        const photos = this.editor.item.photos;

        if (photos.length <= 1) {
          return;
        }

        let newIndex = photoIndex;

        if (up) {
          newIndex -= 1;

          if (newIndex < 0) {
            newIndex = photos.length - 1;
          }
        } else {
          newIndex += 1;

          if (newIndex > (photos.length - 1)) {
            newIndex = 0;
          }
        }

        photos.splice(photoIndex, 1);
        photos.splice(newIndex, 0, photo);
      },
      deletePhoto (photo) {
        const photoIndex = this.findPhotoIndex(photo);

        if (photoIndex === -1) {
          return;
        }

        this.editor.item.photos.splice(photoIndex, 1);
      },
      getFormattedPrice ({ price }) {
        return Intl.NumberFormat([], {
          style: 'currency',
          currency: 'RUB',
        }).format(price);
      },
    },
  }
</script>
