drop type Aroma force;
/
drop type Profil force;
/
drop table users force;
/
drop table vanzari force;
/
drop table proprietati force;
/
drop table produse force;
/

-- Objects

create or replace type Aroma as object(
  a_mere number(5),
  a_pere number(5),
  a_portocale number(5),
  a_lamai number(5),
  a_struguri number(5),
  a_cirese number(5),
  a_visine number(5),
  a_capsuni number(5),
  a_grapefruit number(5),
  a_ananas number(5),
  a_fructe_de_padure number(5),
  constructor function Aroma return self as result  
);
/

create or replace type body Aroma as
  constructor function Aroma return self as result as
  begin
    a_mere := 0;
    a_pere := 0;
    a_portocale := 0;
    a_lamai := 0;
    a_struguri := 0;
    a_cirese := 0;
    a_visine := 0;
    a_capsuni := 0;
    a_grapefruit := 0;
    a_ananas := 0;
    a_fructe_de_padure := 0;
    return;
  end;
end;
/


create or replace type Profil as object(
  url_avatar varchar2(200),
  url_descriere varchar2(200),
  gender number(1),
  regiune varchar(20),
  member function get_gender return number,
  constructor function Profil return self as result
);
/

create or replace type body Profil as

  member function get_gender return number is
    begin
      return self.gender;
    end;

  constructor function Profil return self as result as
    begin
      url_avatar := null;
      url_descriere := null;
      gender := 0;
      regiune := null;
      return;
    end;
end;
/

-- Tables

create table users(
  username varchar2(20) not null,
  password varchar2(20) not null,
  nume varchar2(20) not null,
  prenume varchar2(30) not null,
  wallet number(10) default 0,
  arome Aroma,
  profile Profil,
  constraint users_pk primary key (username)
);
/


create table proprietati(
  id_proprietati number(10) not null,
  --id_produs_fk not null, 
  tip number(1) not null, -- natural/acidulat
  url_descriere varchar2(100),
  arome Aroma,
  constraint proprietati_pk primary key (id_proprietati)
);
/


create table produse(
  id_produs number(10) not null,
  denumire varchar2(30) not null,
  stoc number(10) not null,
  regiune varchar(20) not null,
  pret number(10) not null,
  id_proprietati not null,
  constraint produse_pk primary key (id_produs),
  constraint fk_proprietati foreign key (id_proprietati) references proprietati(id_proprietati)
);
/


create table vanzari(
  username varchar2(20) not null, 
  id_produs number(10) not null,
  data_cumparare date not null,
  constraint fk_users foreign key (username) references users(username),
  constraint fk_produse foreign key (id_produs) references produse(id_produs)
);
/

-- Indexes

drop index vanzari_idx;
create index vanzari_idx on vanzari (username, id_produs);
/


-- Triggers

--set define off;
create or replace trigger auto_proprietati
before insert on proprietati
for each row
when (new.id_proprietati is null)
declare
  v_max numeric(5);
begin
  select count(*) into v_max from proprietati;
  if v_max != 0 then  
    select max(id_proprietati)
    into   v_max
    from   proprietati;
  end if;
  :new.id_proprietati := v_max + 1;
end;
/

create or replace trigger auto_produs
before insert on produse
for each row
when (new.id_produs is null)
declare
  v_max numeric(5);
begin
  select count(*) into v_max from produse;
  if v_max != 0 then  
    select max(id_produs)
    into   v_max
    from   produse;
  end if;
  :new.id_produs := v_max + 1;
end;
/

create or replace trigger update_arome
after insert on vanzari
for each row
declare
  v_arome Aroma;
begin
  select arome into v_arome from 
    (select arome from proprietati where id_proprietati = 
      (select id_proprietati from produse where id_produs = :new.id_produs));
  user_pkg.add_aroma (:new.username, v_arome);
end;
/


-- Packages

create or replace package produse_pkg is
  
  procedure add_produs
  (p_denumire in produse.denumire%type,
  p_stoc in produse.stoc%type,
  p_regiune in produse.regiune%type,
  p_pret in produse.pret%type,
  p_tip in proprietati.tip%type,
  p_url_descriere in proprietati.url_descriere%type,
  p_arome in proprietati.arome%type);
  
  function new_aroma
  (p_arome in varchar2)
  return Aroma;
  
  function get_aroma
  (p_id_produs in produse.id_produs%type)
  return Aroma;
  
  function get_aroma_string(ar Aroma) 
  return varchar2;
  
end produse_pkg;
/
 
create or replace package body produse_pkg is

  procedure add_produs
  (p_denumire in produse.denumire%type,
  p_stoc in produse.stoc%type,
  p_regiune in produse.regiune%type,
  p_pret in produse.pret%type,
  p_tip in proprietati.tip%type,
  p_url_descriere in proprietati.url_descriere%type,
  p_arome in proprietati.arome%type) is
    p_id number(5);
  begin
    insert into proprietati(tip, url_descriere, arome) 
    values (p_tip, p_url_descriere, p_arome);
    
    select max(id_proprietati) into p_id from proprietati;
    
    insert into produse(denumire, stoc, regiune, pret, id_proprietati) 
    values (p_denumire, p_stoc, p_regiune, p_pret, p_id);
  end;

  function new_aroma (p_arome in varchar2) return Aroma as
    v_aroma Aroma;
    v_max number(5);
    val varchar2(100);
  begin
    v_aroma := new Aroma;
    select count(regexp_substr(p_arome,'[^,]+', 1, level)) into v_max from dual
    connect by regexp_substr(p_arome, '[^,]+', 1, level) is not null;
    for i in 1..v_max
    loop
      select regexp_substr(p_arome,'[^,]+', 1, i) into val from dual;
      case val
        when 'mere' then v_aroma.a_mere:=1;
        when 'pere' then v_aroma.a_pere:=1;
        when 'portocale' then v_aroma.a_portocale:=1;
        when 'lamai' then v_aroma.a_lamai:=1;
        when 'struguri' then v_aroma.a_struguri:=1;
        when 'cirese' then v_aroma.a_cirese:=1;
        when 'visine' then v_aroma.a_visine:=1;
        when 'capsuni' then v_aroma.a_capsuni:=1;
        when 'grapefruit' then v_aroma.a_grapefruit:=1;
        when 'ananas' then v_aroma.a_ananas:=1;
        when 'fructe_de_padure' then v_aroma.a_fructe_de_padure:=1;
      end case;
    end loop;
    return v_aroma;
  end;
  
  function get_aroma (p_id_produs in produse.id_produs%type) return Aroma as
    v_aroma Aroma;
    v_id proprietati.id_proprietati%type;
  begin
    select id_proprietati into v_id from produse 
    where id_produs = p_id_produs;
    
    select arome into v_aroma from proprietati
    where v_id = id_proprietati;
    
    return v_aroma;
  end;
  
  function get_aroma_string(ar Aroma) return varchar2 is
    v_aux varchar2(1000) := '';
  begin
    if ar.a_mere >= 1 then
      v_aux := v_aux||'mere, ';
    end if;
    if ar.a_pere >= 1 then
      v_aux := v_aux||'pere, ';
    end if;
    if ar.a_portocale >= 1 then
      v_aux := v_aux||'portocale, ';
    end if;
    if ar.a_lamai >= 1 then
      v_aux := v_aux||'lamai, ';
    end if;
    if ar.a_struguri >= 1 then
      v_aux := v_aux||'struguri, ';
    end if;
    if ar.a_cirese >= 1 then
      v_aux := v_aux||'cirese, ';
    end if;
    if ar.a_visine >= 1 then
      v_aux := v_aux||'visine, ';
    end if;
    if ar.a_capsuni >= 1 then
      v_aux := v_aux||'capsuni, ';
    end if;
    if ar.a_grapefruit >= 1 then
      v_aux := v_aux||'grapefruit, ';
    end if;
    if ar.a_ananas >= 1 then
      v_aux := v_aux||'ananas, ';
    end if;
    if ar.a_fructe_de_padure >= 1 then
      v_aux := v_aux||'fructe_de_padure, ';
    end if;
    return v_aux;
  end;

end produse_pkg;
/


ccreate or replace directory CSV_DIR as 'D:\CSV';

create or replace package csv_pkg is

  procedure load_csv;  
  procedure save_csv;

end csv_pkg;
/

--(denumire, stoc, regiune, pret)
--(tip, url_descriere, arome) 

create or replace package body csv_pkg is

  procedure load_csv is
    v_file utl_file.file_type;
    v_line varchar2(1000);
    e_csv exception;
    e_open_csv exception;
    v_denumire produse.denumire%type;
    v_stoc produse.stoc%type;
    v_regiune produse.regiune%type;
    v_pret produse.pret%type;
    v_tip proprietati.tip%type;
    v_url_descriere proprietati.url_descriere%type;
    v_arome proprietati.arome%type;
    i number(5) := 0;
    v_max number(5);
    v_aux varchar2(20);
  begin
    v_file := utl_file.fopen('CSV_DIR','info.csv','r');
    if not utl_file.is_open(v_file) then
      raise e_open_csv;
    end if;
    
    loop
      begin
        utl_file.get_line(v_file, v_line, 1000);
        if v_line is null then
          exit;
        end if;
        i := i + 1;
        select count(regexp_substr(v_line,'[^,]+', 1, level)) into v_max from dual
        connect by regexp_substr(v_line, '[^,]+', 1, level) is not null;
        
        if v_max <= 6 then
          raise e_csv;
        end if;
        v_denumire := trim(regexp_substr(v_line, '[^,]+', 1, 1));
        v_stoc := trim(regexp_substr(v_line, '[^,]+', 1, 2));
        v_regiune := trim(regexp_substr(v_line, '[^,]+', 1, 3));
        v_pret := trim(regexp_substr(v_line, '[^,]+', 1, 4));
        v_tip := trim(regexp_substr(v_line, '[^,]+', 1, 5));
        v_url_descriere := trim(regexp_substr(v_line, '[^,]+', 1, 6));
        v_aux := '';
        for j in 7..v_max loop
          v_aux := v_aux || trim(regexp_substr(v_line, '[^,]+', 1, j));
          if j < v_max then
            v_aux := v_aux ||',';
          end if;
        end loop;
        v_arome := produse_pkg.new_aroma (v_aux);        
        produse_pkg.add_produs (v_denumire, v_stoc, v_regiune, v_pret, v_tip, v_url_descriere, v_arome);
        exception
        when NO_DATA_FOUND then
          exit;
      end;
    end loop;    
    if utl_file.is_open(v_file) then
       utl_file.fclose(v_file);
    end if;
    exception
      when e_csv then
        dbms_output.put_line('Eroare la linia: '|| i);
        rollback;
        if utl_file.is_open(v_file) then
          utl_file.fclose(v_file);
        end if;
      when e_open_csv then
        dbms_output.put_line('Eroare la deschiderea CSV-ului!');
        if utl_file.is_open(v_file) then
          utl_file.fclose(v_file);
        end if;
  end;
  
  procedure save_csv as
    v_file utl_file.file_type;
    e_csv exception;
    e_open_csv exception;
    v_aux varchar2(1000) := '';
  begin
    v_file := utl_file.fopen('CSV_DIR','info-save.csv','w');
    if not utl_file.is_open(v_file) then
      raise e_open_csv;
    end if;
    for j in (select denumire, stoc, regiune, pret, tip, url_descriere, arome from produse p1, proprietati p2 where p1.id_proprietati = p2.id_proprietati)
    loop
      v_aux := j.denumire||', '||j.stoc||', '||j.regiune||', '||j.pret||', '||j.tip||', '||j.url_descriere;
      v_aux := v_aux||', '||produse_pkg.get_aroma_string(j.arome);
      
      utl_file.put_line(v_file,v_aux);  
    end loop;
    utl_file.fclose(v_file);
  end;

end csv_pkg;
/


create or replace package user_pkg is

  procedure exist_user (f_user in varchar2, f_pass in varchar2, f_return out number);
  procedure change_password (p_user in varchar2, new_pass in varchar2); 
  procedure add_aroma ( p_user in varchar2, p_aroma in Aroma);
  procedure gen_users (nr_users in number);
  procedure add_new_user (
    p_user in varchar2, 
    p_pass in varchar2, 
    p_nume in varchar2, 
    p_prenume in varchar2);
  procedure set_profil(
    p_user in varchar2,
    p_avatar in varchar2,
    p_descriere in varchar2,
    p_gender in number,
    p_regiune in varchar2);
  procedure buy_product( p_user in varchar2, p_produs in number);
  function Get3Pref (utilizator in varchar2) return varchar2;
  
end user_pkg;
/


create or replace package body user_pkg is

  procedure exist_user (f_user in varchar2, f_pass in varchar2, f_return out number) is
    v_user users.username%type;  
  begin
    select username into v_user from users
    where username = f_user and password = f_pass;
    f_return :=  1;
  exception
    when no_data_found then
      f_return := 0;
  end;

  procedure change_password (p_user in varchar2, new_pass in varchar2) is
  begin
    update users
    set password = new_pass
    where username = p_user;
  end;

  procedure add_aroma ( p_user in varchar2, p_aroma in Aroma) is
    v_aroma Aroma;
    e_no_data exception;
  begin
    --v_aroma := new Aroma;
    select arome into v_aroma from users where username = p_user;
    v_aroma.a_mere := v_aroma.a_mere + p_aroma.a_mere;
    v_aroma.a_pere := v_aroma.a_pere + p_aroma.a_pere;
    v_aroma.a_portocale := v_aroma.a_portocale + p_aroma.a_portocale;
    v_aroma.a_lamai := v_aroma.a_lamai + p_aroma.a_lamai;
    v_aroma.a_struguri := v_aroma.a_struguri + p_aroma.a_struguri;
    v_aroma.a_cirese := v_aroma.a_cirese + p_aroma.a_cirese;
    v_aroma.a_visine := v_aroma.a_visine + p_aroma.a_visine;
    v_aroma.a_capsuni := v_aroma.a_capsuni + p_aroma.a_capsuni;
    v_aroma.a_grapefruit := v_aroma.a_grapefruit + p_aroma.a_grapefruit;
    v_aroma.a_ananas := v_aroma.a_ananas + p_aroma.a_ananas;
    v_aroma.a_fructe_de_padure := v_aroma.a_fructe_de_padure + p_aroma.a_fructe_de_padure;
    update users
    set arome = v_aroma
    where username = p_user;
  exception
    when no_data_found then
      raise e_no_data;
  end;
  
  procedure gen_users (nr_users in number) is   
    nr number(5) := 1;
    new_user varchar2(20);
    new_pass varchar2(20);
    new_nume varchar2(20);
    new_prenume varchar2(20);
    v_wallet number(5);
    new_arome Aroma;
    new_profile Profil;
    e_dup_val exception;
  begin
    
    for i in 1..nr_users
    loop
      new_arome := new Aroma;
      new_user := 'user' || nr;
      new_pass := 'pass' || nr;
      new_nume := 'nume' || nr;
      new_prenume := 'prenume' || nr;
      nr := nr + 1;
      v_wallet := dbms_random.value(1,1000);
      new_profile := new Profil;
      dbms_output.put_line(new_profile.gender);
      insert into users (username, password, nume, prenume, wallet, arome, profile) 
        values (new_user, new_pass, new_nume, new_prenume, v_wallet, new_arome, new_profile);    
    end loop;
  exception
    when dup_val_on_index then
      raise e_dup_val;
  end;

  procedure add_new_user (
    p_user in varchar2, 
    p_pass in varchar2, 
    p_nume in varchar2, 
    p_prenume in varchar2) is
    v_arome Aroma := new Aroma;
    v_profil Profil := new Profil;
    e_dup_val exception;
  begin    
    insert into users (username, password, nume, prenume, arome, profile) 
    values (p_user, p_pass, p_nume, p_prenume, v_arome, v_profil);
  exception
    when dup_val_on_index then
      raise e_dup_val;
  end;
  
  procedure set_profil(
    p_user in varchar2,
    p_avatar in varchar2,
    p_descriere in varchar2,
    p_gender in number,
    p_regiune in varchar2) is
    v_profil Profil;
  begin
    v_profil.url_avatar := p_avatar;
    v_profil.url_descriere := p_descriere;
    v_profil.gender := p_gender;
    v_profil.regiune := p_regiune;
    update users
    set profile = v_profil
    where p_user = username;
  end;
  
  procedure buy_product( p_user in varchar2, p_produs in number) is  
  begin
    insert into vanzari values (p_user, p_produs, sysdate);
  end;
  
  function Get3Pref (utilizator in varchar2) return varchar2 is
    v_userAroma Aroma;
    type array_aroma is table of number index by varchar2(100);
    user_array array_aroma;
    produs_array_aroma array_aroma;
    v_user_max_max varchar(20);
    v_user_max_mij varchar(20);
    v_user_max_min varchar(20);
    v_index varchar(20);
    p_unu number(5);
    p_doi number(5);
    p_trei number(5);
    cursor produse_nec is
      select prop.arome, prop.id_proprietati from proprietati prop where
        id_proprietati in
        (select prod.id_produs from produse prod
            where prod.id_produs not in (select v.id_produs from vanzari v where v.username = utilizator and rownum <= 10)) order by dbms_random.value;
  
  begin
    v_userAroma := new Aroma;
    select arome into v_userAroma from users where username = utilizator;
    user_array('mere') := v_userAroma.a_mere;
    user_array('pere') := v_userAroma.a_pere;
    user_array('portocale') := v_userAroma.a_portocale;
    user_array('lamai') := v_userAroma.a_lamai;
    user_array('struguri') := v_userAroma.a_struguri;
    user_array('cirese') := v_userAroma.a_cirese;
    user_array('visine') := v_userAroma.a_visine;
    user_array('capsuni') := v_userAroma.a_capsuni;
    user_array('grapefruit') := v_userAroma.a_grapefruit;
    user_array('ananas') := v_userAroma.a_ananas;
    user_array('fructe_de_padure') := v_userAroma.a_fructe_de_padure;
    
    --determinare index preferinte    
    v_index := user_array.first;
    v_user_max_max := v_index;
    v_user_max_mij := v_index;
    v_user_max_min := v_index;
    while (v_index is not null) --aflam maximul
    loop
      if user_array(v_user_max_max) < user_array(v_index) then v_user_max_max := v_index;
      end if;
      v_index := user_array.next(v_index);
    end loop; --while
    
    v_index := user_array.first;
    while (v_index is not null) --aflam mijlociul
    loop
      if user_array(v_user_max_mij) < user_array(v_index) and user_array(v_user_max_mij) < user_array(v_user_max_max) then v_user_max_mij := v_index;
      end if;
      v_index := user_array.next(v_index);
    end loop; --while
    
    v_index := user_array.first;
    while (v_index is not null) -- aflam minimul
    loop
      if user_array(v_user_max_min) < user_array(v_index) and user_array(v_user_max_min) < user_array(v_user_max_mij) then v_user_max_min := v_index;
      end if;
      v_index := user_array.next(v_index);
    end loop; --while
    
    --pentru fiecare produs din cursor -> Do !
    for pro in produse_nec
    loop
      produs_array_aroma('mere') := pro.arome.a_mere;
      produs_array_aroma('pere') := pro.arome.a_pere;
      produs_array_aroma('portocale') := pro.arome.a_portocale;
      produs_array_aroma('lamai') := pro.arome.a_lamai;
      produs_array_aroma('struguri') := pro.arome.a_struguri;
      produs_array_aroma('cirese') := pro.arome.a_cirese;
      produs_array_aroma('visine') := pro.arome.a_visine;
      produs_array_aroma('capsuni') := pro.arome.a_capsuni;
      produs_array_aroma('grapefruit') := pro.arome.a_grapefruit;
      produs_array_aroma('ananas') := pro.arome.a_ananas;
      produs_array_aroma('fructe_de_padure') := pro.arome.a_fructe_de_padure;
      
      if produs_array_aroma(v_user_max_max) = 1 then p_unu := pro.id_proprietati;
      end if;
      if produs_array_aroma(v_user_max_mij) = 1 then p_doi := pro.id_proprietati;
      end if;
      if produs_array_aroma(v_user_max_min) = 1 then p_trei := pro.id_proprietati;
      end if;
      
    end loop;
    return to_char(p_unu) || '?' || to_char(p_doi) || '?' || to_char(p_trei);
  end;
  
end user_pkg;
/

 
-- drop index categorie_idx;
-- create index categorie_idx on produse (id_categorie);

-- drop table categorie;
-- create table categorie(
--   id_categorie number(10) not null,
--   c_natural number(1) default 0,
--   c_acidulat number(1) default 0,
--   c_aroma_primara varchar2 (20),
--   c_aroma_secundara varchar2 (20),
--   constraint categorie_pk primary key (id_categorie)
-- );




-- Anonymous block


create or replace procedure gen_produse (nr_produs in number) is
  nr number(5) := 1;
  new_denumire produse.denumire%type;
  new_stoc produse.stoc%type;
  new_regiune produse.regiune%type;
  new_pret produse.pret%type;
  new_tip proprietati.tip%type;
  new_url_descriere proprietati.url_descriere%type;
  new_arome proprietati.arome%type;
  v_aroma varchar2(50);
  e_dup_val exception;
begin
  
  for i in 1..nr_produs
  loop
    v_aroma := '';
    if i mod 2 = 0 then
      v_aroma := v_aroma || 'mere,';
    else
      v_aroma := v_aroma ||'pere,';
    end if;
    if i mod 3 = 0 then
      v_aroma := v_aroma ||'portocale,';
    end if;
    if i mod 5 = 0 then
      v_aroma := v_aroma ||'fructe_de_padure,';
    end if;
    new_arome := produse_pkg.new_aroma(v_aroma);
    new_denumire := 'produs' || nr;
    new_stoc := dbms_random.value(1,1000);
    new_regiune := 'Moldova';
    new_pret := dbms_random.value(4,20);
    new_tip := dbms_random.value(0,1);
    new_url_descriere := '/desc_prod'||'/'||new_denumire||'.txt';
    
    nr := nr + 1;
    produse_pkg.add_produs(new_denumire, new_stoc, new_regiune, new_pret, new_tip, new_url_descriere, new_arome);
  end loop;
exception
  when dup_val_on_index then
    raise e_dup_val;
end;
/


create or replace procedure gen_vanzari(p_user varchar2, p_max number) is
  nr_gen number(5);
  v_id number(5);
begin
  nr_gen := dbms_random.value(1,p_max/2);
  for i in 1..nr_gen
  loop
    v_id := dbms_random.value(1,p_max);
    insert into vanzari
    values(p_user, v_id, sysdate);
  end loop;
end;
/

set serveroutput on;

begin
  user_pkg.gen_users(3);
end;
/

begin
  gen_produse(17);
--  for i in 1..17
--  loop
--    dbms_output.put_line(produse_pkg.get_aroma_string(produse_pkg.get_aroma(i)));
--  end loop;
end;
/

-- Adaugari produs si get_aroma

-- declare
--  ar Aroma;
--  ar2 Aroma;
-- begin
--  ar := produse_pkg.new_aroma('mere,pere,struguri,fructe_de_padure');
--  -- denumire / stoc / pret / tip / url / aroma
--  produse_pkg.add_produs('produs1', 100, 'Moldova', 5, 1, 'url_prod1', ar);

--  ar2 := produse_pkg.get_aroma(1);
--  dbms_output.put_line(ar2.a_mere||' '||ar2.a_pere||' '||ar2.a_portocale||' '||ar2.a_grapefruit||' '||ar2.a_fructe_de_padure);
-- end;

-- declare
--   v_ar Aroma;
--   produse_line produse%rowtype;
--   produse_line.id_produs;
-- begin
--   v_ar := new Aroma;
-- insert into proprietati(tip, url_descriere, arome) values (0, 'weweq', v_ar);
-- end;


-- Exemplu de regex

--declare
--  text varchar2(100) := 'mere,pere,capsuni,grapefruit';
--  v_max number(5);
--  val varchar2(100);
--begin
--  select count(regexp_substr(text,'[^,]+', 1, level)) into v_max from dual
--  connect by regexp_substr(text, '[^,]+', 1, level) is not null;
--  for i in 1..v_max
--  loop
--    select regexp_substr(text,'[^,]+', 1, i) into val from dual;
--    dbms_output.put_line(val);
--  end loop;
--end;

-- Other

select u.profile.gender from users u;

select * from users;
/

delete from users;
