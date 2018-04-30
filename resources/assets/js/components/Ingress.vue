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
                        <i class="fa fa-align-justify"></i> Ingress
                        <button type="button" @click="openmodal('ingress','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
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
                                            <button type="button" @click="openmodal('ingress','update',ingress)" class="btn btn-success btn-sm" title="Editar">
                                              <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="ingress.status=='Registrado'">
                                                <button type="button" @click="desactiveIngress(ingress.id)" class="btn btn-danger btn-sm" title="Estado">
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
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <select class="form-control">
                                        
                                    </select>
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
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Artículo</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="idarticle" placeholder="Ingrese Artículo">
                                        <button class="btn btn-primary">...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" VALUE="" class="form-control" v-model="price">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" class="form-control" v-model="quantity">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnagregar">
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
                                    <tbody>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                Artículo n
                                            </td>
                                            <td>
                                                <input type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                $ 6.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                Artículo n
                                            </td>
                                            <td>
                                                <input type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                $ 6.00
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Parcial:</strong>
                                            </td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Impuesto:</strong>
                                            </td>
                                            <td>$ 1</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right">
                                                <strong>Total Neto:</strong>
                                            </td>
                                            <td>$ 6</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registerIngress()">Registrar Compra</button>
                            </div>
                        </div>
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
                arrayIngress: [],
                arrayDetail: [],
                
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
                buscar : ''
            } 
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
            selectRole(){
                let me=this;
                var url='/role/selectRole';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRole = respuesta.roles;
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
                me.listIngress(page,buscar,criterio);
            },
            registerPerson(){
                if(this.validatePerson()){
                    return;
                }

                let me = this;

                axios.post('/user/registrar', {
                    'name' : this.name,
                    'type_document' : this.type_document,
                    'num_document' : this.num_document,
                    'address' : this.address,
                    'phone' : this.phone,
                    'email' : this.email,
                    'user' : this.user,
                    'password' : this.password,
                    'idrole' : this.idrole

                }).then(function (response) {
                    me.closemodal();
                    me.listPerson(1,'','name');
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
            validatePerson(){
                this.errorPerson = 0;
                this.errorShowMssgPerson = [];

                if (!this.name) this.errorShowMssgPerson.push("el nombre de la persona no puede estar vacio");
                if (!this.user) this.errorShowMssgPerson.push("el nombre de usuario no puede estar vacio");
                if (!this.password) this.errorShowMssgPerson.push("el password no puede estar vacio");
                if (this.idrole == 0) this.errorShowMssgPerson.push("Debes seleccionar un rol para el usuario");

                if (this.errorShowMssgPerson.length) this.errorPerson = 1;

                return this.errorPerson;
            },
            closemodal(){
                this.modal=0;
                this.titlemodal='';
                this.name='';
                this.type_document='DNI';
                this.num_document='';
                this.address='';
                this.phone='';
                this.email='';
                this.user='';
                this.password='';
                this.idrole=0;
                this.errorPerson= 0;
            },
            // se crea un metodo de abrir modal donde se le pasa tres parametros
            openmodal(modelo, accion, data = []){
                this.selectRole();
                switch(modelo){
                    case "person":
                    {
                        switch(accion){
                            case "register":
                            {
                                this.modal = 1;
                                this.titlemodal = "Registrar Usuario";
                                this.name = '';
                                this.type_document = 'DNI';
                                this.num_document ='';
                                this.address ='';
                                this.phone ='';
                                this.email ='';
                                this.user='';
                                this.password='';
                                this.idrole=0;
                                this.typeaction = 1;
                                break;
                            }
                            case "update":
                            {
                                //console.log(data);
                                this.modal=1;
                                this.titlemodal="Actualizar Usuario";
                                this.typeaction=2;
                                this.person_id= data['id'];
                                this.name = data ['name'];
                                this.type_document = data ['type_document'];
                                this.num_document = data ['num_document'];
                                this.address = data ['address'];
                                this.phone = data ['phone'];
                                this.email = data ['email'];
                                this.user = data ['user'];
                                this.password = data ['password'];
                                this.idrole = data ['idrole'];
                                break;
                            }
                        }
                    }
                }
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
