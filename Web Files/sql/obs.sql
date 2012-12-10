/*==============================================================*/
/* DBMS name:      ORACLE Version 10g                           */
/* Created on:     10/10/2012 2:24:54 PM                        */
/*==============================================================*/


alter table ADMINISTRATOR
   drop constraint FK_ADMINIST_TYPEOFUSE_USERS;

alter table ATTRIBUTENAME
   drop constraint FK_ATTRIBUT_CATATT_CATEGORY;

alter table CREDIT_CARD
   drop constraint FK_CREDIT_C_CUSTOMERC_CUSTOMER;

alter table CUSTOMER
   drop constraint FK_CUSTOMER_TYPEOFUSE_USERS;

alter table INVOICE
   drop constraint FK_INVOICE_CREDITINV_CREDIT_C;

alter table INVOICE
   drop constraint FK_INVOICE_CUSTOMERI_CUSTOMER;

alter table ITEM
   drop constraint FK_ITEM_ITEM_PRODUCT;

alter table ITEM
   drop constraint FK_ITEM_ITEM2_CATEGORY;

alter table PRODUCTSUBCAT
   drop constraint FK_PRODUCTS_PRODUCTSU_PRODUCT;

alter table PRODUCTSUBCAT
   drop constraint FK_PRODUCTS_PRODUCTSU_SUBCAT;

alter table SESSIONS
   drop constraint FK_SESSIONS_USERSESSI_USERS;

alter table SHOPPING_CART
   drop constraint FK_SHOPPING_SHOPPING__INVOICE;

alter table SHOPPING_CART
   drop constraint FK_SHOPPING_SHOPPING__ITEM;

alter table SUBCAT
   drop constraint FK_SUBCAT_CATEGORYS_CATEGORY;

drop table ADMINISTRATOR cascade constraints;

drop index CATATT_FK;

drop table ATTRIBUTENAME cascade constraints;

drop table CATEGORY cascade constraints;

drop index CUSTOMERCREDIT_FK;

drop table CREDIT_CARD cascade constraints;

drop table CUSTOMER cascade constraints;

drop index CREDITINVOICE_FK;

drop index CUSTOMERINVOICE_FK;

drop table INVOICE cascade constraints;

drop index ITEM2_FK;

drop index ITEM_FK;

drop table ITEM cascade constraints;

drop table PRODUCT cascade constraints;

drop index PRODUCTSUBCAT2_FK;

drop index PRODUCTSUBCAT_FK;

drop table PRODUCTSUBCAT cascade constraints;

drop index USERSESSION_FK;

drop table SESSIONS cascade constraints;

drop index SHOPPING_CART2_FK;

drop index SHOPPING_CART_FK;

drop table SHOPPING_CART cascade constraints;

drop index CATEGORYSUBCAT_FK;

drop table SUBCAT cascade constraints;

drop table USERS cascade constraints;

/*==============================================================*/
/* Table: ADMINISTRATOR                                         */
/*==============================================================*/
create table ADMINISTRATOR  (
   EMAIL                VARCHAR2(50)                    not null,
   constraint PK_ADMINISTRATOR primary key (EMAIL)
);

/*==============================================================*/
/* Table: ATTRIBUTENAME                                         */
/*==============================================================*/
create table ATTRIBUTENAME  (
   ATTID                VARCHAR2(5)                     not null,
   CATID                VARCHAR2(5)                     not null,
   ATTNAME              VARCHAR2(25),
   constraint PK_ATTRIBUTENAME primary key (ATTID)
);

/*==============================================================*/
/* Index: CATATT_FK                                             */
/*==============================================================*/
create index CATATT_FK on ATTRIBUTENAME (
   CATID ASC
);

/*==============================================================*/
/* Table: CATEGORY                                              */
/*==============================================================*/
create table CATEGORY  (
   CATID                VARCHAR2(5)                     not null,
   CATNAME              VARCHAR2(50),
   constraint PK_CATEGORY primary key (CATID)
);

/*==============================================================*/
/* Table: CREDIT_CARD                                           */
/*==============================================================*/
create table CREDIT_CARD  (
   CARDID               VARCHAR2(5)                     not null,
   EMAIL                VARCHAR2(50)                    not null,
   CARDNO               NUMBER,
   CARDNAME             VARCHAR2(50),
   EXPIRYDATE           VARCHAR2(10),
   CVV                  NUMBER,
   constraint PK_CREDIT_CARD primary key (CARDID)
);

/*==============================================================*/
/* Index: CUSTOMERCREDIT_FK                                     */
/*==============================================================*/
create index CUSTOMERCREDIT_FK on CREDIT_CARD (
   EMAIL ASC
);

/*==============================================================*/
/* Table: CUSTOMER                                              */
/*==============================================================*/
create table CUSTOMER  (
   EMAIL                VARCHAR2(50)                    not null,
   UNIT_NO              VARCHAR2(10),
   STREET               VARCHAR2(25),
   CITY                 VARCHAR2(25),
   STATE                VARCHAR2(3),
   POSTCODE             NUMBER,
   constraint PK_CUSTOMER primary key (EMAIL)
);

/*==============================================================*/
/* Table: INVOICE                                               */
/*==============================================================*/
create table INVOICE  (
   INVOICENO            VARCHAR2(5)                     not null,
   CARDID               VARCHAR2(5)                     not null,
   EMAIL                VARCHAR2(50)                    not null,
   TOTALPRICE           NUMBER(8,2),
   PAID                 SMALLINT,
   constraint PK_INVOICE primary key (INVOICENO)
);

/*==============================================================*/
/* Index: CUSTOMERINVOICE_FK                                    */
/*==============================================================*/
create index CUSTOMERINVOICE_FK on INVOICE (
   EMAIL ASC
);

/*==============================================================*/
/* Index: CREDITINVOICE_FK                                      */
/*==============================================================*/
create index CREDITINVOICE_FK on INVOICE (
   CARDID ASC
);

/*==============================================================*/
/* Table: ITEM                                                  */
/*==============================================================*/
create table ITEM  (
   PRODUCTID            VARCHAR2(5)                     not null,
   CATID                VARCHAR2(5)                     not null,
   ATTVAL	            VARCHAR(1024),
   PICTURE              VARCHAR(100),
   constraint PK_ITEM primary key (PRODUCTID, CATID)
);

/*==============================================================*/
/* Index: ITEM_FK                                               */
/*==============================================================*/
create index ITEM_FK on ITEM (
   PRODUCTID ASC
);

/*==============================================================*/
/* Index: ITEM2_FK                                              */
/*==============================================================*/
create index ITEM2_FK on ITEM (
   CATID ASC
);

/*==============================================================*/
/* Table: PRODUCT                                               */
/*==============================================================*/
create table PRODUCT  (
   PRODUCTID            VARCHAR2(5)                     not null,
   PRODNAME             VARCHAR2(50),
   DESCRIPTION          VARCHAR2(1024),
   constraint PK_PRODUCT primary key (PRODUCTID)
);

/*==============================================================*/
/* Table: PRODUCTSUBCAT                                         */
/*==============================================================*/
create table PRODUCTSUBCAT  (
   PRODUCTID            VARCHAR2(5)                     not null,
   SCATID               VARCHAR2(5)                     not null,
   constraint PK_PRODUCTSUBCAT primary key (PRODUCTID, SCATID)
);

/*==============================================================*/
/* Index: PRODUCTSUBCAT_FK                                      */
/*==============================================================*/
create index PRODUCTSUBCAT_FK on PRODUCTSUBCAT (
   PRODUCTID ASC
);

/*==============================================================*/
/* Index: PRODUCTSUBCAT2_FK                                     */
/*==============================================================*/
create index PRODUCTSUBCAT2_FK on PRODUCTSUBCAT (
   SCATID ASC
);

/*==============================================================*/
/* Table: SESSIONS                                              */
/*==============================================================*/
create table SESSIONS  (
   SESSIONID            VARCHAR2(5)                     not null,
   EMAIL                VARCHAR2(50)                    not null,
   constraint PK_SESSIONS primary key (SESSIONID)
);

/*==============================================================*/
/* Index: USERSESSION_FK                                        */
/*==============================================================*/
create index USERSESSION_FK on SESSIONS (
   EMAIL ASC
);

/*==============================================================*/
/* Table: SHOPPING_CART                                         */
/*==============================================================*/
create table SHOPPING_CART  (
   INVOICENO            VARCHAR2(5)                     not null,
   PRODUCTID            VARCHAR2(5)                     not null,
   CATID                VARCHAR2(5),
   QUANTITY             NUMBER,
   PRICE                NUMBER(8,2),
   constraint PK_SHOPPING_CART primary key (INVOICENO, PRODUCTID)
);

/*==============================================================*/
/* Index: SHOPPING_CART_FK                                      */
/*==============================================================*/
create index SHOPPING_CART_FK on SHOPPING_CART (
   INVOICENO ASC
);

/*==============================================================*/
/* Index: SHOPPING_CART2_FK                                     */
/*==============================================================*/
create index SHOPPING_CART2_FK on SHOPPING_CART (
   PRODUCTID ASC,
   CATID ASC
);

/*==============================================================*/
/* Table: SUBCAT                                                */
/*==============================================================*/
create table SUBCAT  (
   SCATID               VARCHAR2(5)                     not null,
   CATID                VARCHAR2(5),
   SCATNAME             VARCHAR2(50),
   constraint PK_SUBCAT primary key (SCATID)
);

/*==============================================================*/
/* Index: CATEGORYSUBCAT_FK                                     */
/*==============================================================*/
create index CATEGORYSUBCAT_FK on SUBCAT (
   CATID ASC
);

/*==============================================================*/
/* Table: USERS                                                 */
/*==============================================================*/
create table USERS  (
   EMAIL                VARCHAR2(50)                    not null,
   FNAME                VARCHAR2(25),
   LNAME                VARCHAR2(25),
   PASSWORD             VARCHAR2(50),
   constraint PK_USERS primary key (EMAIL)
);

alter table ADMINISTRATOR
   add constraint FK_ADMINIST_TYPEOFUSE_USERS foreign key (EMAIL)
      references USERS (EMAIL);

alter table ATTRIBUTENAME
   add constraint FK_ATTRIBUT_CATATT_CATEGORY foreign key (CATID)
      references CATEGORY (CATID);

alter table CREDIT_CARD
   add constraint FK_CREDIT_C_CUSTOMERC_CUSTOMER foreign key (EMAIL)
      references CUSTOMER (EMAIL);

alter table CUSTOMER
   add constraint FK_CUSTOMER_TYPEOFUSE_USERS foreign key (EMAIL)
      references USERS (EMAIL);

alter table INVOICE
   add constraint FK_INVOICE_CREDITINV_CREDIT_C foreign key (CARDID)
      references CREDIT_CARD (CARDID);

alter table INVOICE
   add constraint FK_INVOICE_CUSTOMERI_CUSTOMER foreign key (EMAIL)
      references CUSTOMER (EMAIL);

alter table ITEM
   add constraint FK_ITEM_ITEM_PRODUCT foreign key (PRODUCTID)
      references PRODUCT (PRODUCTID);

alter table ITEM
   add constraint FK_ITEM_ITEM2_CATEGORY foreign key (CATID)
      references CATEGORY (CATID);

alter table PRODUCTSUBCAT
   add constraint FK_PRODUCTS_PRODUCTSU_PRODUCT foreign key (PRODUCTID)
      references PRODUCT (PRODUCTID);

alter table PRODUCTSUBCAT
   add constraint FK_PRODUCTS_PRODUCTSU_SUBCAT foreign key (SCATID)
      references SUBCAT (SCATID);

alter table SESSIONS
   add constraint FK_SESSIONS_USERSESSI_USERS foreign key (EMAIL)
      references USERS (EMAIL);

alter table SHOPPING_CART
   add constraint FK_SHOPPING_SHOPPING__INVOICE foreign key (INVOICENO)
      references INVOICE (INVOICENO);

alter table SHOPPING_CART
   add constraint FK_SHOPPING_SHOPPING__ITEM foreign key (PRODUCTID, CATID)
      references ITEM (PRODUCTID, CATID);

alter table SUBCAT
   add constraint FK_SUBCAT_CATEGORYS_CATEGORY foreign key (CATID)
      references CATEGORY (CATID);

