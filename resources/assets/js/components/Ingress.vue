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
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="showDetail()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- Listado -->
                    <template v-if="listado">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="type_voucher">Tipo Comprobante</option>
                                      <option value="num_voucher">Número Comprobante</option>
                                      <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listIngress(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listIngress(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Voucher</th>
                                        <th>Serie Voucher</th>
                                        <th>Número Voucher</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- :key indica la llave principal 
                                        tabla dinamica-->
                                    <tr v-for="ingress in arrayIngress" :key="ingress.id">
                                        <td>
                                            <button type="button" @click="openmodal('ingress','update',ingress)" class="btn btn-success btn-sm">
                                              <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="ingress.status=='Registrado'">
                                                <button type="button" @click="desactiveIngress(ingress.id)" class="btn btn-danger btn-sm">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="ingress.user"></td>
                                        <td v-text="ingress.name"></td>
                                        <td v-text="ingress.type_voucher"></td>
                                        <td v-text="ingress.serie_voucher"></td>
                                        <td v-text="ingress.num_voucher"></td>
                                        <td v-text="ingress.fecha_hora"></td>
                                        <td v-text="ingress.total"></td>
                                        <td v-text="ingress.tax"></td>
                                        <td v-text="ingress.status"></td>
                                    </tr>
                                    <!-- fin de tabla dinamica -->
                                    
                                </tbody>
                            </table>
                        </div>
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
                    </template>
                    <!-- Fin Listado -->
                    <!-- Detalle -->
                    <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select
                                        :on-search="selectProvider"
                                        label="name"
                                        :options="arrayProvider"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDataProvider"                                        
                                    >

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="tax">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de Comprobante(*)</label>
                                    <select class="form-control" v-model="type_voucher">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_voucher" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_voucher" placeholder="000xx">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorIngress" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMssgIngress" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Artículo <span style="color:red;" v-show="idarticle==0">(*Seleccione)</span> </label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="searchArticle()"placeholder="Ingrese Artículo">
                                        <button @click="openmodal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="article">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio<span style="color:red;" v-show="price==0">(*Ingrese)</span> </label>
                                    <input type="number" VALUE="" class="form-control" v-model="price">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad<span style="color:red;" v-show="quantity==0">(*Ingrese)</span> </label>
                                    <input type="number" class="form-control" v-model="quantity">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="addDetail()" class="btn btn-success form-control btnagregar">
                                        <i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Artículo</th>   
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tbody v-if="arrayDetail.length">
                                        <tr v-for="(detail,index) in arrayDetail" :key="detail.id">
                                            <td>
                                                <button @click="deleteDetail(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detail.article">
                                            </td>
                                            <td>
                                                <input v-model="detail.price" type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detail.quantity" type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                {{detail.price*detail.quantity}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Parcial:</strong>
                                            </td>
                                            <td>$ {{totalPartial=(total-totalTax).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Impuesto:</strong>
                                            </td>
                                            <td>$ {{totalTax=((total*tax)/(1+tax)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Neto:</strong>
                                            </td>
                                            <td>$ {{total=calculateTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="hideDetail() ">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registerIngress()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Detalle -->

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
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioA">
                                          <option value="name">Nombre</option>
                                          <option value="description">Descripción</option>
                                          <option value="code">Código</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listArticle(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listArticle(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- :key indica la llave principal 
                                            tabla dinamica-->
                                        <tr v-for="article in arrayArticle" :key="article.id">
                                            <td>
                                                <button type="button" @click="addDetailModal(article)" class="btn btn-success btn-sm">
                                                  <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="article.code"></td>
                                            <td v-text="article.name"></td>
                                            <td v-text="article.name_category"></td>
                                            <td v-text="article.price"></td>
                                            <td v-text="article.stock"></td>
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closemodal()">Cerrar</button>
                            <button type="button" v-if="typeaction==1" class="btn btn-primary" @click="registerPerson()">Guardar</button>
                            <button type="button" v-if="typeaction==2" class="btn btn-primary" @click="updatePerson()">Actualizar</button>
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
import vSelect from 'vue-select';
    export default {
        data (){
            return{
                ingress_id: 0,
                idprovider:0,
                name :'',
                type_voucher : 'BOLETA',
                serie_voucher : '',
                num_voucher : '',
                tax: 0.18,
                total: 0.0,
                totalTax: 0.0,
                totalPartial: 0.0,
                arrayIngress: [],
                arrayProvider: [],
                arrayDetail: [],
                listado: 1,
                
                modal : 0,
                titlemodal : '',
                typeaction : 0,
                errorIngress : 0,
                errorShowMssgIngress : [],
                pagination : {
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0,
                },
                offset : 3,
                criterio : 'num_voucher',
                buscar : '',
                criterioA: 'name',
                buscarA: '',
                arrayArticle: [],
                idarticle: 0,
                code: '',
                article: '',
                price: 0,
                quantity: 0
            } 
        },
        components: {
            vSelect
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
            },
            calculateTotal: function(){
                var resultado = 0.0;
                for(var i=0; i<this.arrayDetail.length; i++){
                    resultado=resultado+(this.arrayDetail[i].price*this.arrayDetail[i].quantity)
                }
                return resultado;
            }

        },
        methods : {
            listIngress(page,buscar,criterio){
                let me=this;
                var url='/ingress?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayIngress = respuesta.income.data;
                    me.pagination = respuesta.pagination;
                  })
                  .catch(function (error) {
                    console.log(error);
                  });
            },
            selectProvider(search,loading){
                let me=this;
                loading(true)

                var url= '/provider/selectProvider?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayProvider=respuesta.providers;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDataProvider(val1){
                let me = this;
                me.loading = true;
                me.idprovider = val1.id;
            },
            searchArticle(){
                let me = this;
                var url = '/article/searchArticle?filtro=' + me.code;

                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayArticle = respuesta.articles;

                    if (me.arrayArticle.length>0){
                        me.article = me.arrayArticle[0]['name'];
                        me.idarticle = me.arrayArticle[0]['id'];
                    }
                    else{
                        me.article = 'No existe artículo';
                        me.idarticle = 0;
                    }
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            changePage(page,buscar,criterio){
                let me = this;
                //actualiza la pagina actual
                me.pagination.current_page = page;
                //envia la peticion para visualizar la data de esa pagina
                me.listIngress(page,buscar,criterio);
            },
            encuentra(id){
                var sw=0;
                for (var i=0; i<this.arrayDetail.length; i++){
                    if(this.arrayDetail[i].idarticle==id){
                        sw=true;
                    }
                }
                return sw;
            },
            deleteDetail(index){
                let me = this;
                me.arrayDetail.splice(index, 1);
            },
            addDetail(){
                let me = this;

                if(me.idarticle==0 || me.quantity==0 || me.price==0){
                }
                else{
                    if(me.encuentra(me.idarticle)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado!',
                        })
                    }
                    else{
                        me.arrayDetail.push({
                            idarticle: me.idarticle,
                            article: me.article,
                            quantity: me.quantity,
                            price: me.price
                        });
                        me.code="";
                        me.idarticle=0;
                        me.article="";
                        me.quantity=0;
                        me.price=0;
                        } 
                }    
            },
            addDetailModal(data = []){
                let me = this;
                if(me.encuentra(data['id'])){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado!',
                        })
                    }
                    else{
                        me.arrayDetail.push({
                            idarticle: data['id'],
                            article: data['name'],
                            quantity: 1,
                            price: 1
                        });
                    }   
            },
            listArticle(buscar,criterio){
                let me=this;
                var url='/article/listArticle?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticle = respuesta.articles.data;
                  })
                  .catch(function (error) {
                    console.log(error);
                  });
            },
            registerIngress(){
                if(this.validateIngress()){
                    return;
                }

                let me = this;

                axios.post('/ingress/registrar', {
                    'idprovider' : this.idprovider,
                    'type_voucher' : this.type_voucher,
                    'serie_voucher' : this.serie_voucher,
                    'num_voucher' : this.num_voucher,
                    'tax' : this.tax,
                    'total' : this.total,
                    'data' : this.arrayDetail

                }).then(function (response) {
                    me.listado=1;
                    me.listIngress(1,'','num_voucher');
                    me.idprovider=0;
                    me.type_voucher='BOLETA';
                    me.serie_voucher='';
                    me.num_voucher='';
                    me.tax=0.18;
                    me.total=0.0;
                    me.idarticle=0;
                    me.article='';
                    me.quantity=0;
                    me.price=0;
                    me.arrayDetail=[];

                }).catch(function(error){
                    console.log(error);
                });
            }, 
            updatePerson(){
                if(this.validatePerson()){
                    return;
                }

                let me = this;

                axios.put('/user/actualizar', {
                    'name' : this.name,
                    'type_document' : this.type_document,
                    'num_document' : this.num_document,
                    'address' : this.address,
                    'phone' : this.phone,
                    'email' : this.email,
                    'user' : this.user,
                    'password' : this.password,
                    'idrole' : this.idrole,
                    'id' : this.person_id
                }).then(function (response) {
                    me.closemodal();
                    me.listPerson(1,'','name');
                }).catch(function(error)
                {
                    console.log(error);
                });
            },
            validateIngress(){
                this.errorIngress = 0;
                this.errorShowMssgIngress = [];

                if (this.idprovider==0) this.errorShowMssgIngress.push("Seleccione un proveedor");
                if (this.type_voucher==0) this.errorShowMssgIngress.push("Seleccione el comprobante");
                if (!this.num_voucher) this.errorShowMssgIngress.push("Seleccione el número de comprobante");
                if (!this.tax) this.errorShowMssgIngress.push("Ingrese el impuesto de compra");
                if (this.arrayDetail.length<=0) this.errorShowMssgIngress.push("Ingrese detalles");


                if (this.errorShowMssgIngress.length) this.errorIngress = 1;

                return this.errorIngress;
            },
            showDetail(){
                let me = this;
                me.listado=0;
                me.idprovider=0;
                me.type_voucher='BOLETA';
                me.serie_voucher='';
                me.num_voucher='';
                me.tax=0.18;
                me.total=0.0;
                me.idarticle=0;
                me.article='';
                me.quantity=0;
                me.price=0;
                me.arrayDetail=[];
            },
            hideDetail(){
                this.listado=1;
            },
            closemodal(){
                this.modal=0;
                this.titlemodal='';
            },
            // se crea un metodo de abrir modal donde se le pasa tres parametros
            openmodal(){
                this.arrayArticle=[];
                this.modal = 1;
                this.titlemodal = "Seleccione uno o varios artículos";
            },
            desactiveUser(id){
                swal({
                      title: 'Esta seguro de desactivar este usuario?',
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

                            axios.put('/user/desactivar', {
                                'id': id
                            }).then(function (response) {
                                me.listPerson(1,'','name');
                                swal(
                                    'Desactivado',
                                    'El usuario ha sido desactivado con exito.',
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
            activeUser(id){
                swal({
                      title: 'Esta seguro de activar este usuario',
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

                            axios.put('/user/activar', {
                                'id': id
                            }).then(function (response) {
                                me.listPerson(1,'','name');
                                swal(
                                    'Activado',
                                    'El usuario ha sido activado con exito.',
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
            }
        },
        mounted() {
            this.listIngress(1,this.buscar,this.criterio);
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

    @media (min-width: 600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>
