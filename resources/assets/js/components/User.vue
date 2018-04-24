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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="openmodal('person','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="name">Nombre</option>
                                      <option value="num_document">Documento</option>
                                      <option value="email">Email</option>
                                      <option value="phone">Teléfono</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listPerson(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listPerson(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo Documento</th>
                                    <th>Número</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- :key indica la llave principal 
                                    tabla dinamica-->
                                <tr v-for="person in arrayPerson" :key="person.id">
                                    <td>
                                        <button type="button" @click="openmodal('person','update',person)" class="btn btn-warning btn-sm" title="Editar">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="person.condition">
                                            <button type="button" @click="desactiveUser(person.id)" class="btn btn-danger btn-sm" title="Estado">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activeUser(person.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="person.name"></td>
                                    <td v-text="person.type_document"></td>
                                    <td v-text="person.num_document"></td>
                                    <td v-text="person.address"></td>
                                    <td v-text="person.phone"></td>
                                    <td v-text="person.email"></td>
                                    <td v-text="person.user"></td>
                                    <td v-text="person.rol"></td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de la persona">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                    <div class="col-md-9">
                                        <select type="text" v-model="type_document" class="form-control">
                                            <option value="DNI">DNI</option>
                                            <option value="RUT">RUT</option>
                                            <option value="PASS">PASS</option>
                                        </select>  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="num_document" class="form-control" placeholder="Ingrese Número de documento">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="address" class="form-control" placeholder="Ingrese Dirección">  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="phone" class="form-control" placeholder="Ingrese Teléfono">  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Ingrese email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Rol (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idrole">
                                            <option value="0">Seleccione un Rol</option>
                                            <option v-for="role in arrayRole" :key="role.id" :value="role.id" v-text="role.name"></option>                                       
                                        </select> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="user" class="form-control" placeholder="Ingrese nombre de Usuario">  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Password (*)</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Ingrese Password de acceso">  
                                    </div>
                                </div>
                                <div v-show="errorPerson" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMssgPerson" :key="error" v-text="error">
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                person_id: 0,
                name :'',
                type_document : 'DNI',
                num_document : '',
                address : '',
                phone : '',
                email : '',
                user : '',
                password : '',
                idrole : 0,
                arrayPerson : [],
                arrayRole : [],
                modal : 0,
                titlemodal : '',
                typeaction : 0,
                errorPerson : 0,
                errorShowMssgPerson : [],
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
            listPerson(page,buscar,criterio){
                let me=this;
                var url='/user?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPerson = respuesta.persons.data;
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
                me.listPerson(page,buscar,criterio);
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
            this.listPerson(1,this.buscar,this.criterio);
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
