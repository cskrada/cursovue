<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Artículos
                        <button type="button" @click="openmodal('article','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="name">Nombre</option>
                                      <option value="description">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listArticle(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listArticle(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- :key indica la llave principal 
                                    tabla dinamica-->
                                <tr v-for="article in arrayArticle" :key="article.id">
                                    <td>
                                        <button type="button" @click="openmodal('article','update',article)" class="btn btn-warning btn-sm" title="Editar">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="article.condition">
                                            <button type="button" @click="desactiveArticle(article.id)" class="btn btn-danger btn-sm" title="Estado">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activeArticle(article.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="article.code"></td>
                                    <td v-text="article.name"></td>
                                    <td v-text="article.name_category"></td>
                                    <td v-text="article.price"></td>
                                    <td v-text="article.stock"></td>
                                    <td v-text="article.description"></td>
                                    <td>
                                        <div v-if="article.condition">
                                        <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                                <!-- fin de tabla dinamica -->   
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="changePage(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" v-bind:class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titlemodal"></h4>
                            <button type="button" class="close" @click="closemodal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idcategory">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="category in arrayCategory" :key="category.id" :value="category.id" v-text="category.name"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="code" class="form-control" placeholder="Código de barras">
                                        <barcode :value="code" :options="{ format: 'EAN-13' }">
                                            Generando código de Barras. 
                                        </barcode>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de artículo">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="price" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="description" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorArticle" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMssgArticle" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closemodal()">Cerrar</button>
                            <button type="button" v-if="typeaction==1" class="btn btn-primary" @click="registerArticle()">Guardar</button>
                            <button type="button" v-if="typeaction==2" class="btn btn-primary" @click="updateArticle()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
          
        </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    export default {
        data (){
            return{
                article_id : 0,
                idcategory : 0,
                name_category : '',
                code : '',
                name : '',
                price : 0,
                stock : 0,
                description : '',
                arrayArticle : [],
                modal : 0,
                titlemodal : '',
                typeaction : 0,
                errorArticle : 0,
                errorShowMssgArticle: [],
                pagination : {
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0,
                },
                offset : 3,
                criterio : 'name',
                buscar : '',
                arrayCategory : []
            } 
        },
        components: {
            'barcode': VueBarcode
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            // calcula los elementos de la paginacion
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {
            listArticle(page,buscar,criterio){
                let me=this;
                var url='/article?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticle = respuesta.articles.data;
                    me.pagination = respuesta.pagination;
                  })
                  .catch(function (error) {
                    console.log(error);
                  });
            },
            selectCategory(){
                let me=this;
                var url= '/category/selectCategory';
                axios.get(url).then(function (response) {
                    // console.log(response);
                    var respuesta= response.data;
                    me.arrayCategory = respuesta.categories;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage(page,buscar,criterio){
                let me = this;
                //actualiza la pagina actual
                me.pagination.current_page = page;
                //envia la peticion para visualizar la data de esa pagina
                me.listArticle(page,buscar,criterio);
            },
            registerArticle(){
                if(this.validateArticle()){
                    return;
                }

                let me = this;

                axios.post('/article/registrar', {
                    'idcategory' : this.idcategory,
                    'code'       : this.code,
                    'name'       : this.name,
                    'stock'      : this.stock,
                    'price'      : this.price,
                    'description': this.description
                }).then(function (response) {
                    me.closemodal();
                    me.listArticle(1,'','name');
                }).catch(function(error){
                    console.log(error);
                });
            }, 
            updateArticle(){
                if(this.validateArticle()){
                    return;
                }

                let me = this;

                axios.put('/article/actualizar', {
                    'idcategory' : this.idcategory,
                    'code'       : this.code,
                    'name'       : this.name,
                    'stock'      : this.stock,
                    'price'      : this.price,
                    'description': this.description,
                    'id'         : this.article_id
                }).then(function (response) {
                    me.closemodal();
                    me.listArticle(1,'','name');
                }).catch(function(error)
                {
                    console.log(error);
                });
            },
            desactiveArticle(id){
                swal({
                      title: 'Esta seguro de desactivar este artículo',
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Aceptar',
                      cancelButtonText: 'Cancelar',
                      confirmButtonClass: 'btn btn-success',
                      cancelButtonClass: 'btn btn-danger',
                      buttonsStyling: false,
                      reverseButtons: true
                    }).then((result) => {
                      if (result.value) {

                        let me = this;

                            axios.put('/article/desactivar', {
                                'id': id
                            }).then(function (response) {
                                me.listArticle(1,'','name');
                                swal(
                                    'Desactivado',
                                    'El artículo ha sido desactivado con exito.',
                                    'success'
                                    )
                            }).catch(function(error)
                            {
                                console.log(error);
                            });
                        
                      } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                      ) {
                       
                      }
                    })
            },
            activeArticle(id){
                swal({
                      title: 'Esta seguro de activar este artículo',
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Aceptar',
                      cancelButtonText: 'Cancelar',
                      confirmButtonClass: 'btn btn-success',
                      cancelButtonClass: 'btn btn-danger',
                      buttonsStyling: false,
                      reverseButtons: true
                    }).then((result) => {
                      if (result.value) {

                        let me = this;

                            axios.put('/article/activar', {
                                'id': id
                            }).then(function (response) {
                                me.listArticle(1,'','name');
                                swal(
                                    'Activado',
                                    'El artículo ha sido activado con exito.',
                                    'success'
                                    )
                            }).catch(function(error)
                            {
                                console.log(error);
                            });
                        
                      } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                      ) {
                       
                      }
                    })
            },
            validateArticle(){
                this.errorArticle = 0;
                this.errorShowMssgArticle = [];

                if(this.idcategory == 0) this.errorShowMssgArticle.push("Seleccione una categoría.");
                if (!this.name) this.errorShowMssgArticle.push("el nombre del artículo no puede estar vacío");
                if (!this.stock) this.errorShowMssgArticle.push("El stock del artículo debe ser un número y no puede estar vacío");
                if (!this.price) this.errorShowMssgArticle.push("El precio venta del artículo debe ser un número y no puede estar vacío");

                if (this.errorShowMssgArticle.length) this.errorArticle = 1;

                return this.errorArticle;
            },
            closemodal(){
                this.modal=0;
                this.titlemodal='';
                this.idcategory= 0;
                this.name_category= '';
                this.code= '';
                this.name='';
                this.price= 0;
                this.stock= 0;
                this.description='';
                this.errorArticle= 0;
            },
            // se crea un metodo de abrir modal donde se le pasa tres parametros
            openmodal(modelo, accion, data = []){
                switch(modelo){
                    case "article":
                    {
                        switch(accion){
                            case "register":
                            {
                                this.modal = 1;
                                this.titlemodal = "Registrar Artículo";
                                this.idcategory = 0;
                                this.name_category ='';
                                this.code = '';
                                this.name = '';
                                this.price = 0;
                                this.stock = 0;
                                this.description = '';
                                this.typeaction = 1;
                                break;
                            }
                            case "update":
                            {
                                //console.log(data);
                                this.modal=1;
                                this.titlemodal="Actualizar Artículo";
                                this.typeaction=2;
                                this.article_id= data['id'];
                                this.idcategory= data['idcategory'];
                                this.code= data['code'];
                                this.name = data ['name'];
                                this.price= data['price'];
                                this.stock= data['stock'];
                                this.description = data ['description'];
                                break;
                            }
                        }
                    }
                }
                this.selectCategory();
            }
        },
        mounted() {
            this.listArticle(1,this.buscar,this.criterio);
        }
    }
</script>
<style type="text/css">
    .modal-content{
        width: 100% !important;
        position: absolute !important; 
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
