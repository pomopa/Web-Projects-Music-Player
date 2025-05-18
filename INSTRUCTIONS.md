# LSpoty - Llenguatges de Programació II
**Grup 06 - Curs 2024-2025**

Aquest projecte simula una plataforma de gestió musical, desenvolupada amb un entorn de CodeIgniter.

**Membres de l’equip:**

1. Joan Enric Peiró Vidal (<joanenric.peiro@salle.url.edu>)
2. Roger Metaute Carrillo (<roger.metaute@salle.url.edu>)
3. Pol Monné Parera (<pol.monne@salle.url.edu>)

---

## Índex

1. [Com iniciar el projecte](#com-iniciar-el-projecte)
2. [Implementació d'endpoints](#implementació-dendpoints)
3. [Aliases de rutes](#aliases-de-rutes)
4. [Traducció del codi](#traducció-del-codi)
5. [Validacions de seguretat](#validacions-de-seguretat)

---

## Com iniciar el projecte

Per tal d'iniciar el projecte es necessari configurar els fitxers d'environment del docker i del CodeIgniter.
La configuració utilitzada durant el seu desenvolupament esta penjada en el git i es la implementada pels membres del
grup. Segueix les següents instruccions per configurar el projecte i la BBDD:

1. Configurar .env del docker
2. docker compose up -d
    ```bash
    docker compose up -d
   ```
3. docker compose exec app composer install
    ```bash
    docker compose exec app composer install
    ```
4. Configurar .env del projecte
5. docker compose exec app php spark migrate
   ```bash
    docker compose exec app php spark migrate
    ```

---

## Implementació d'endpoints

Els endpoints implementats són els requerits per l'enunciat però es voldrien destacar els següents
degut a incoherencies en aquest els següents enpoints no s'han implementat sota les instruccions del professorat:

- `POST /artist/{id}`
- `POST /album/{id}`
- `POST /playlist/{id}`
- `POST /my-playlist/{id}`

També es vol remarcar que en el cas de `PUT /my-playlist/{id}`, i també sota les ordres del professorat,
s'ha modificat la funcionalitat que esmentava l'enunciat. Tot i que l'enunciat comentava que aquest endpoint
havia de servir per crear una nova playlist això no tenia sentit degut a que ja existeix el endpoint
`POST /create-playlist` i que l'ús del mètode PUT està pensat per actualitzar dades i no crear-les. Conseqüentment,
aquest enpoint ha implementat la funcionalitat d'actualització de les dades d'una playlist.

---

## Aliases de rutes

S’han definit aliases per facilitar la lectura i ús de les rutes. Cada ruta té un alias definit que permet
identificar-la i enrutar-la de manera única dins del sistema. Alguns exemples són els següents:

- `landing_view` → Alias de `/`
- `sign-up_success` → Alias de `/sign-up/success`
- ...

Tots els aliases estan documentats en el fitxer de rutes.

---

## Traducció del projecte

El projecte ha estat traduït completament al **català**, incloent:

- Textos del frontend
- Comentaris explicatius
- Respostes d’error i validació
- Alerts del JavaScript

Aquesta traducció s'implementa de manera automàtica en el sistema sempre que l'usuari
tingui el navegador configurat en aquest idioma. En cas contrari, l'idioma per defecte de la pàgina és
el anglès.
---

## Validacions de seguretat

Durant el desenvolupament del projecte s'ha vetllat per implementar una pàgina segura i robusta en front
d'atacs maliciosos, reforçant tant com s'ha pogut tots els aspectes del Frontend i Backend. Alguns exemples
de seguretat implementada són:

- **Sanitització d’inputs**: totes les entrades són validades tant en el frontend com en el backend.
- **Control d’autenticació**: accés restringit a certes rutes per usuari registrat mitjançant l'ús de filtres.
- **Validacions de les imatges**: es valida que els fitxers penjats siguin del tipus permès, que el mimetype coincideix amb l'extensió i únicament es provi de penjar-ne un.
- **Verificació de fitxers**: En qualsevol ruta POST no controlada pel filtre d'imatges es fa un control per comprovar que l'usuari no prova d'enviar un fitxer al servidor.
- **Emmagatzematge d'imatges**: Les imatges s'emmagatzemen a la carpeta `/writable` i són retornades per un controlador per tal de ser accedides de manera segura des del frontend. Un usuari únicament pot accedir a les seves imatges un cop registrat.
- **Estructura de carpetes**: Les carpetes dins de `writable/uploads` s'han estructurat de manera que cada usuari té un directori amb el seu id i dins d'aquest les carpetes `/profile` i `/playlists`. Això s'ha fet per modular l'emmagatzematge i en cas d'escalar evitar repeticions de noms.

Aquestes mesures, entre altres, garanteixen un entorn mínimament segur per a l’execució del servei.

---

## Carbon

Per complir amb el requisit de l'enunciat d'implementar una dependència més fent ús de Composer s'ha optat per l'implementació de Carbon. Aquesta API permet obtenir l'hora actual del sistema i mostrar-la als usuaris.

---

## Endpoint `/track/{id}`

Tot i no ser requerit per l'enunciat aquest enpoint s'ha implementat per tal de poder visualitzar el detall
d'una cançó. Permetent des de múltiples punts de la pàgina un accés directe a l'informació detallada de cançons
concretes d'àlbums o playlists.