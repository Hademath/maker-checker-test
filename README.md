## NOTE 

CHECK THE SQL FORDER TO GET THE database for this implementation
database_name:maker_checker-kredimoney


## The endpoint are listed here for testing

# POST :register user
 endpoint: http://127.0.0.1:8000/api/auth/register
   sample body:
   {
    "firstname":"Sogo",
    "lastname":"Abiola",
    "email":"olorppuo@gmail.com",
    "password":"1A8hn@",
    "phone":"09037325234"
}
# POST : login user 
endpoint : http://127.0.0.1:8000/api/auth/login

sample body:
    {
    "email":"ade@gmail.com",
    "password":"123456"
}

# POST :Register Admin 

endpoint: http://127.0.0.1:8000/api/adminauth/admin_register
SAMPLE body :{
    "name":"Olusegun Ajao",
    "email":"ajao@gmail.com",
    "phone":"090236676798",
    "role":"customer officer",
    "password":"123456"

}
 # POST :Admin LOGIN 
 ENDPOINT :http://127.0.0.1:8000/api/adminauth/admin_login
 sample body:{
    "email":"ajao@gmail.com",
     "password":"123456"
}

 # GET : admin get all users
 endpoint: http://127.0.0.1:8000/api/admin/all_users
 response sample : {
    "status": true,
    "status_code": 200,
    "message": "Successful",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "firstname": "ade",
                "lastname": "leke",
                "email": "ade@gmail.com",
                "phone": null,
                "status": "1",
                "created_at": "2022-06-05T11:47:50.000000Z",
                "updated_at": "2022-06-05T11:47:50.000000Z"
            },
            {
                "id": 3,
                "firstname": "Abiola",
                "lastname": "ayo",
                "email": "hademath@gmail.com",
                "phone": null,
                "status": "1",
                "created_at": "2022-06-05T12:01:14.000000Z",
                "updated_at": "2022-06-05T12:01:14.000000Z"
            },
    
     
        ],
        "first_page_url": "http://127.0.0.1:8000/api/admin/all_users?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:8000/api/admin/all_users?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/all_users?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://127.0.0.1:8000/api/admin/all_users",
        "per_page": 15,
        "prev_page_url": null,
        "to": 5,
        "total": 5
    },
    "token": null,
    "debug": null
}


# GET : Admin Get User BY id
end point: http://127.0.0.1:8000/api/admin/get_user/1

## POST: ONly super admin can edit the user's detail that has been approved by customer officer
end point http://127.0.0.1:8000/api/admin/edit_user/1
sample body:
{
    "firstname":"Ebuka",
    "lastname":"Crownaire",
    "email":"zainb@gmail.com"
}
If the successfully edited, emmail will be sent to customer officer that the approved users a]has been edited or updated successfully. If
NOTE: the email is not working, google recently bring down the third party accessibilty for security purpose on the gmail, so It can't create 
connection with the stmp. This can be done with other third party OR setting up mail forwarding on the website to be able to implement this. 
## GET: Authorise admin can view pending request

end point : http://127.0.0.1:8000/api/admin/pending_request


## POST : customer officer can approve or deactivate/decline the pending user 

approve endpoint: http://127.0.0.1:8000/api/admin/approve_user/5

decline endpoint : http://127.0.0.1:8000/api/admin/decline_user/3


## POST : Admin can logout of the application
endpoing : http://127.0.0.1:8000/api/admin/logout