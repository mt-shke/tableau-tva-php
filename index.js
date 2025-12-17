"use strict";

const btnAjouter = document.querySelector(".btn-ajouter");
const btnSupprimerLigne = document.querySelector(".btn-supprimer-ligne");
const btnEffacerLigne = document.querySelector(".btn-effacer-ligne");
const ulList = document.querySelector(".ul-list");
const totalPrixTTC = document.querySelectorAll(".prixttc");
// const btnSaveJson = document.querySelector(".btn-save-json");
// const btnLoadJson = document.querySelector(".btn-load-json");
// const btnSaveLocal = document.querySelector(".btn-save-local");
// const btnLoadLocal = document.querySelector(".btn-load-local");

btnAjouter.addEventListener("click", () => {
   const li = document.createElement("li");
   li.innerHTML = `
                  <label class="border-list"><input placeholder="libellé"></input></label>
                  <label class="border-list"><input type="number" class='prixht' placeholder="prix HT"></input></label>
                  <select class="border-list tva">
                     <option>20%</option>
                     <option>5.5%</option>
                  </select>
                  <label class="border-list"><input type="number" class='prixttc' placeholder="prix TTC"></input></label>
                  <div class="list-button-container">
                     <button class='btn-green btn-calculer'>Calculer</button>
                     <button class='btn-yellow btn-effacer'>Effacer</button>
                     <button class='btn-red btn-supprimer'>Supprimer</button>
                  </div>
       
        `;
   ulList.appendChild(li);
   displayPopup("popup-ajouter");
});

// BOUTONS TABLEAU

btnSupprimerLigne.addEventListener("click", () => {
   const lis = Array.from(ulList.children)[1];
   if (!lis) {
      return;
   }

   const lastElement = ulList.lastElementChild;
   if (lastElement) {
      lastElement.remove();
      displayPopup("popup-supprimer");
   }
});

btnEffacerLigne.addEventListener("click", () => {
   const tousLesInputs = ulList.querySelectorAll("li input");
   tousLesInputs.forEach((input) => {
      input.value = "";
   });
   displayPopup("popup-effacer");
});

// BOUTON LI

ulList.addEventListener("click", (e) => {
   if (e.target.matches(".btn-supprimer")) {
      // const lis = Array.from(ulList.children)[1];
      // if (!lis) {
      //    return;
      // }
      // const liParent = e.target.closest("li"); //
      // liParent.remove();
      // displayPopup("popup-supprimer-1");
      calculateTotal();
   }

   if (e.target.matches(".btn-effacer")) {
      const li = e.target.closest("li");
      const inputsDuLi = li.querySelectorAll("input");
      inputsDuLi.forEach((input) => (input.value = ""));
      displayPopup("popup-effacer-1");
      calculateTotal();
   }

   if (e.target.matches(".btn-calculer")) {
      const li = e.target.closest("li");
      const prixht = Number(li.querySelector(".prixht").value);
      const tva = li.querySelector(".tva").value;
      const taxe = (prixht * tva) / 100;
      const ttc = taxe + prixht;
      li.querySelector(".prixttc").value = ttc.toFixed(2);
   }
});

// CALCUL TOTAL

const calculateTotal = () => {
   const prixttc = ulList.querySelectorAll(".prixttc");
   let somme = 0;
   prixttc.forEach((input, index) => {
      const valeur = parseFloat(input.value) || 0;
      somme += valeur;
   });
   document.querySelector(".totalttc").value = somme.toFixed(2);
};

// INPUT CHANGE

ulList.addEventListener("input", (e) => {
   const li = e.target.closest("li");
   const prixht = Number(li.querySelector(".prixht").value);
   const tva = li.querySelector(".tva").value;
   const taxe = (prixht * tva) / 100;
   const ttc = taxe + prixht;
   li.querySelector(".prixttc").value = ttc.toFixed(2);
   calculateTotal();
});

// POPUP
const displayPopup = (popupName) => {
   const elem = document.querySelector("." + popupName);
   elem.className = `popup ${popupName}`;
   -setTimeout(() => {
      elem.className = `popup ${popupName} hidden`;
   }, 2000);
};

// // JSON

// btnSaveJson.addEventListener("click", () => {
//    const items = Array.from(ulList.querySelectorAll("li"));
//    const data = items.map((item) =>
//       Array.from(item.querySelectorAll("label input")).map((ite) => ite.value)
//    );

//    downloadJson(data);
// });

// const downloadJson = (data, filename = "liste.json") => {
//    const json = JSON.stringify(data, null, 2);
//    const blob = new Blob([json], { type: "application/json" });
//    const url = URL.createObjectURL(blob);

//    const a = document.createElement("a");
//    a.href = url;
//    a.download = filename;
//    a.click();

//    URL.revokeObjectURL(url);
// };

// btnLoadJson.addEventListener("change", function (e) {
//    const file = e.target.files[0];
//    if (!file) return;

//    const reader = new FileReader();
//    reader.onload = function (event) {
//       const jsonData = JSON.parse(event.target.result);

//       ulList.innerHTML = "";

//       jsonData.forEach((item) => {
//          const li = document.createElement("li");
//          li.innerHTML = `
//                   <label class="border-list"><input placeholder="libellé" value=${item[0]}></input></label>
//                   <label class="border-list"><input type="number" class='prixht' placeholder="prix HT" value=${item[1]}></input></label>
//                   <select class="border-list tva">
//                      <option>20%</option>
//                      <option>5.5%</option>
//                   </select>
//                   <label class="border-list"><input type="number" class='prixttc' placeholder="prix TTC" value=${item[2]}></input></label>
//                   <div class="list-button-container">
//                      <button class='btn-green btn-calculer'>Calculer</button>
//                      <button class='btn-yellow btn-effacer'>Effacer</button>
//                      <button class='btn-red btn-supprimer'>Supprimer</button>
//                   </div>

//         `;
//          ulList.appendChild(li);
//       });
//       calculateTotal();
//    };
//    reader.readAsText(file);
// });

// // LOCAL STORAGE

// btnSaveLocal.addEventListener("click", () => {
//    const items = Array.from(ulList.querySelectorAll("li"));
//    const data = items.map((item) =>
//       Array.from(item.querySelectorAll("label input")).map((ite) => ite.value)
//    );

//    localStorage.setItem("liste", JSON.stringify(data));
// });

// btnLoadLocal.addEventListener("click", function (e) {
//    const stored = localStorage.getItem("liste");
//    const data = JSON.parse(stored);
//    console.log(data);

//    ulList.innerHTML = "";

//    data.forEach((item) => {
//       const li = document.createElement("li");
//       li.innerHTML = `
//                   <label class="border-list"><input placeholder="libellé" value=${item[0]}></input></label>
//                   <label class="border-list"><input type="number" class='prixht' placeholder="prix HT" value=${item[1]}></input></label>
//                   <select class="border-list tva">
//                      <option>20%</option>
//                      <option>5.5%</option>
//                   </select>
//                   <label class="border-list"><input type="number" class='prixttc' placeholder="prix TTC" value=${item[2]}></input></label>
//                   <div class="list-button-container">
//                      <button class='btn-green btn-calculer'>Calculer</button>
//                      <button class='btn-yellow btn-effacer'>Effacer</button>
//                      <button class='btn-red btn-supprimer'>Supprimer</button>
//                   </div>

//         `;
//       ulList.appendChild(li);
//    });
//    calculateTotal();
// });
