@extends('app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card px-5 py-5">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h4>Customer</h4>
                        </div>
                        <div class="align-items-center col">
                            <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 btn-sm bg-gradient-primary">Create</button>
                        </div>
                    </div>
                    <hr class="bg-dark "/>
                    <table class="table" id="tableData">
                        <thead>
                        <tr class="bg-light">
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Mobile</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="tableList">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    //Create Customer
    <div class="modal" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Customer</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control" id="customerName">
                                    <label class="form-label">Customer Email *</label>
                                    <input type="text" class="form-control" id="customerEmail">
                                    <label class="form-label">Customer Address *</label>
                                    <input type="text" class="form-control" id="customerAddress">
                                    <label class="form-label">Customer Mobile *</label>
                                    <input type="text" class="form-control" id="customerMobile">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn btn-sm  btn-success" >Save</button>
                </div>
            </div>
        </div>
    </div>

    //Update Customer
    <div class="modal" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control" id="customerNameUpdate">
                                    <label class="form-label">Customer Email *</label>
                                    <input type="text" class="form-control" id="customerEmailUpdate" readonly>
                                    <label class="form-label">Customer Address *</label>
                                    <input type="text" class="form-control" id="customerAddressUpdate">
                                    <label class="form-label">Customer Mobile *</label>
                                    <input type="text" class="form-control" id="customerMobileUpdate">
                                    <input type="text" class="d-none" id="updateID">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="update-modal-close" class="btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Update()" id="update-btn" class="btn btn-sm  btn-success" >Update</button>
                </div>
            </div>
        </div>
    </div>

    // Delete Customer
    <div class="modal" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 class=" mt-3 text-warning">Delete !</h3>
                    <p class="mb-3">Once delete, you can't get it back.</p>
                    <input class="d-none" id="deleteID"/>

                </div>
                <div class="modal-footer justify-content-end">
                    <div>
                        <button type="button" id="delete-modal-close" class="btn shadow-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="itemDelete()" type="button" id="confirmDelete" class="btn shadow-sm btn-danger" >Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        getList();


        async function getList() {
            showLoader();
            let res=await axios.get("/list-customer");
            hideLoader();

            let tableList=$("#tableList");
            let tableData=$("#tableData");

            tableData.DataTable().destroy();
            tableList.empty();

            res.data.forEach(function (item,index) {
                let row=`<tr>
                    <td>${index+1}</td>
                    <td>${item['name']}</td>
                    <td>${item['email']}</td>
                    <td>${item['address']}</td>
                    <td>${item['mobile']}</td>
                    <td>
                        <button data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
                        <button data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                    </td>
                 </tr>`
                tableList.append(row)
            })

            $('.editBtn').on('click', async function () {
                let id= $(this).data('id');
                await FillUpUpdateForm(id)
                $("#update-modal").modal('show');
            })

            $('.deleteBtn').on('click',function () {
                let id= $(this).data('id');
                $("#delete-modal").modal('show');
                $("#deleteID").val(id);
            })

            new DataTable('#tableData',{
                order:[[0,'desc']],
                lengthMenu:[5,10,15,20,30]
            });

        }

        //Create Customer
        async function Save() {

            let customerName = document.getElementById('customerName').value;
            let customerAddress = document.getElementById('customerEmail').value;
            let customerEmail = document.getElementById('customerAddress').value;
            let customerMobile = document.getElementById('customerMobile').value;

            if (customerName.length === 0) {
                errorToast("Customer Name Required !")
            }
            else if(customerEmail.length===0){
                errorToast("Customer Email Required !")
            }
            else if(customerAddress.length===0){
                errorToast("Customer Address Required !")
            }
            else if(customerMobile.length===0){
                errorToast("Customer Mobile Required !")
            }
            else {

                document.getElementById('modal-close').click();

                showLoader();
                let res = await axios.post("/create-customer",{
                    name:customerName,
                    email:customerEmail,
                    address:customerAddress,
                    mobile:customerMobile
                })
                hideLoader();

                if(res.status===201){

                    successToast('Request completed');

                    document.getElementById("save-form").reset();

                    await getList();
                }
                else{
                    errorToast("Request fail !")
                }
            }
        }

        //update Customer
        async function FillUpUpdateForm(id){
            document.getElementById('updateID').value=id;
            showLoader();
            let res=await axios.post("/customer-by-id",{id:id})
            hideLoader();
            document.getElementById('customerNameUpdate').value=res.data['name'];
            document.getElementById('customerEmailUpdate').value=res.data['email'];
            document.getElementById('customerAddressUpdate').value=res.data['address'];
            document.getElementById('customerMobileUpdate').value=res.data['mobile'];
        }


        async function Update() {

            let customerName = document.getElementById('customerNameUpdate').value;
            let customerEmail = document.getElementById('customerEmailUpdate').value;
            let customerAddress = document.getElementById('customerAddressUpdate').value;
            let customerMobile = document.getElementById('customerMobileUpdate').value;
            let updateID = document.getElementById('updateID').value;


            if (customerName.length === 0) {
                errorToast("Customer Name Required !")
            }
            else if(customerAddress.length===0){
                errorToast("Customer Address Required !")
            }
            else if(customerMobile.length===0){
                errorToast("Customer Mobile Required !")
            }
            else {

                document.getElementById('update-modal-close').click();

                showLoader();

                let res = await axios.post("/update-customer",{
                    name:customerName,
                    email:customerEmail,
                    address:customerAddress,
                    mobile:customerMobile,
                    id:updateID
                })

                hideLoader();

                if(res.status===200 && res.data===1){

                    successToast('Request completed');

                    document.getElementById("update-form").reset();

                    await getList();
                }
                else{
                    errorToast("Request fail !")
                }
            }
        }

        //Delete Customer
        async  function  itemDelete(){
            let id=document.getElementById('deleteID').value;
            document.getElementById('delete-modal-close').click();
            showLoader();
            let res=await axios.post("/delete-customer",{id:id})
            hideLoader();
            if(res.data===1){
                successToast("Request completed")
                await getList();
            }
            else{
                errorToast("Request fail!")
            }
        }


    </script>
@endsection
