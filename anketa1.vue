<template>
  <div class="wrapp-anketa">
    <fieldset id="anketa">
      <legend>Anketa</legend>
      <div v-if="imaanketa">
        <div v-for="anketa in data" :key="anketa.anketa_id" class="izlistaj">
          <h3 id="h3-naslov-anketa">{{anketa.naslov}}</h3>
          <div v-for="pitanje in anketa.pitanje" :key="pitanje.pitanje_id" class="pitanja">
            <h4 id="h4-pitanje-anketa">{{pitanje.pitanje}}</h4>
            <div v-for="odgovor in pitanje.odgovor" :key="odgovor.odgovor_id" class="odgovori">
              <table>
                <tr>
                  <td>
                    <input
                      type="radio"
                      :name="pitanje.pitanje_id"
                      :value="odgovor.odgovor_id"
                      @click="unesiOdgovor(pitanje.pitanje_id, odgovor.odgovor_id)"
                    />
                  </td>
                  <td>{{odgovor.odgovor}}</td>
                </tr>
              </table>
              
            </div>
          </div>
        </div>
        <br />
        <button @click="posaljiOdgovor">Pošalji odgovor</button>
      </div>
      <div v-if="pokazirezultat">
        <div v-for="anketa in podaci" :key="anketa.anketa_id" class="rezultati">
          <h2 id="h2-naslov-anketa">{{anketa.naslov}}</h2>
          <div v-for="pitanje in anketa.pitanje" :key="pitanje.pitanje_id" class="pitanja">
            <h3 id="h3-pitanje-anketa">{{pitanje.pitanje}}</h3>
            <table class="odgovor">
              <tr v-for="odgovor in pitanje.odgovor" :key="odgovor.odgovor_id">
                <td>{{odgovor.odgovor}}</td>
                <td class="pomoc">
                  <div
                    class="procenat"
                    :id="odgovor.odgovor_id"
                    v-bind:style="{width: odgovor.procent + '%'}"
                  ></div>
                </td>
                <td>{{odgovor.procent +'%'}}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </fieldset>
  </div>
</template>

<script>
import axios from "axios";
//axios.defaults.withCredentials = true;
export default {
  data() {
    return {
      anketa_id: null,
      data: [],
      imaanketa: false,
      odgovori: [],
      pokazirezultat: false,
      podaci: []
    };
  },
  mounted() {
    axios
      .get("...mars-hosting.com/api/anketa")
      .then(response => {
        //localStorage.setItem('sid',response.data.sid)
        this.data = response.data.anketa;

        //console.log("procitana anketa");
        var err = response.data.err;
        if (err != null || err != undefined) {
          alert(err);
          this.imaanketa = false;
          //console.log(this.data.pitanje);
        } else {
          this.imaanketa = true;
          this.anketa_id = this.data[0].anketa_id;
          //alert(this.anketa_id);
        }
      });
  },
  methods: {
    posaljiOdgovor() {
      //console.log(this.odgovori);
      if (this.odgovori.length == 0) {
        alert("Popunite anketu pa onda Kliknite dugme Posalji odgovor");
        return;
      }
      //axios.defaults.withCredentials = true;
      let params = {};
      params.odgovori = this.odgovori;
      params.anketa_id = this.anketa_id;

      axios.get("http://019q122.mars-e1.mars-hosting.com/api/upisankete", {
              params: params,
              headers: {
                withCredentials: true
                     //'Access-Control-Allow-Headers': '*',
                     //'Access-Control-Allow-Credentials': true,
                     
                      }
            })
        .then(response => {
          console.log(response.data.gost_id);
          var err = response.data.err;
          if (err != null || err != null) {
            alert(err);
            this.pokazirezultat = false;
          } else {
            this.imaanketa = false;
            this.pokazirezultat = true;
            //alert(response.data.result);
            this.podaci = response.data.data;
          }
        });
    },
    unesiOdgovor(pitanje_id, odgovor_id) {
      //console.log(this.odgovori);
      var odgovor = {
        pitanje_id: pitanje_id,
        odgovor_id: odgovor_id
      };

      for (var i = 0; i < this.odgovori.length; i++) {
        if (this.odgovori[i].pitanje_id == pitanje_id) {
          this.odgovori[i].odgovor_id = odgovor_id;
          return;
        }
      }
      this.odgovori.push(odgovor);
    }
  }
};
</script>

<style>
.odgovori,
.odgovor {
  margin: 0 auto;
  width: 100%;
}
.pomoc {
  width: 100%;
}
.procenat {
  background: blue;
  border-radius: 5px;
  /* padding: 5px; */
  height: 10px;
  color: white;
}
#anketa {
  width: 75%;
  background: lightyellow;
  font-size: 1.3vmin;
}
#h3-naslov-anketa {
  font-size: 1.8vmin;
}
#h2-naslov-anketa {
  font-size: 1.8vmin;
}
#h4-pitanje-anketa {
  font-size: 1.5vmin;
}
#h3-pitanje-anketa {
  font-size: 1.5vmin;
}
</style>