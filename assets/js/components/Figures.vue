<template>
    <div>
        <button @click="createFigures">Generate</button>
        <button @click="changeFigures">Change Figure</button>

        <div>
            <div class="figure-block" v-for="figure in figures">
                <span v-bind:style="{backgroundColor: figure.color}">{{ figure.type }}</span>
                <div>
                    <p>Changes</p>
                    <div v-for="item in figure.changes">
                        <div v-if="item.action === 'create'">
                            <span>Figure created</span>
                            <button @click="revertChanges(item.id)">Revert</button>
                        </div>
                        <div v-else v-for="field in item.data">
                            <span>{{ field.field }}: {{ field.old_value }} -> {{ field.new_value }}</span>
                            <button @click="revertChanges(item.id)">Revert</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  import {createFigures, changeFigures, revertChanges, getFigures} from '../helpers/api'
  export default {
    data: function () {
      return {
        figures: [],
        batchId: '',
      }
    },
    mounted() {

    },
    methods: {
      createFigures: function () {
        createFigures().then(response => {
          this.figures = response.data.figures
          this.batchId = response.data.batchId
        });
      },
      changeFigures: function () {
        changeFigures(this.batchId).then(response => {
          this.figures = this.figures.map(obj => obj.id === response.data.id ? response.data : obj);
        });
      },
      revertChanges: function (id) {
        revertChanges(id).then(response => {
          getFigures(this.batchId).then(response => {
            this.figures = response.data.figures
          })
        });
      },
    }
  }
</script>
<style scoped>
    .figure-block {
        margin: 20px;
    }
</style>
