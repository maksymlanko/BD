import random, string, psycopg2

numUsers=0
numLocais=30
numItens=100
numAnom=30
numIncidencias=15
anomCutter=1
insert = "INSERT INTO {} ({}) VALUES({});\n"

tabelas={
    "local_publico": [],
    "item": [],
    "anomalia": [],
    "anomalia_traducao": [],
    "duplicado": [],
    "utilizador": [],
    "ur": [],
    "uq": [],
    "incidencia": [],
    "proposta_de_correcao": [],
    "correcao": []
}

def user(tup, qual=0):
    tmp = random.randint(0,3)
    if qual==1 or tmp==0:
        text2 = "qualificado"
        tabelas["uq"].append(tup[0])
    else:
        text2 = "regular"
        tabelas["ur"].append(tup[0])

    tabelas["utilizador"].append(tup)
    text = insert.format("utilizador", "email, password", "'"+tup[0] + "', '"+tup[1]+"'") + insert.format("utilizador_{}".format(text2), "email", "'"+tup[0]+"'")
    return text

def local(lat=0, lon=0, text=''):
    if(text==''):
        text = stringGen()
    tmp=coorGen()
    if(lat==0):
        lat=float("{:.6f}".format(tmp[0]))
    else:
        lat=float("{:.6f}".format(lat))
    if(lon==0):
        lon=float("{:.6f}".format(tmp[1]))
    else:
        lon=float("{:.6f}".format(lon))
    tabelas["local_publico"].append((lat,lon,text))
    return insert.format("local_publico", "latitude, longitude, nome", "'"+str(lat)+"', '"+str(lon)+"', '"+text+"'")

def item():
    text = stringGen(size=20)
    text2 = stringGen()
    a=random.choice(tabelas["local_publico"])
    lat=a[0]
    lon=a[1]
    tabelas["item"].append((text, text2, lat, lon))
    return insert.format("item", "descricao, localizacao, latitude, longitude", "'"+text+"', '"+text2+"', '"+str(lat)+"', '"+str(lon)+"'")

def anomalia():
    zona=[random.randint(0,4999),random.randint(0,4999)]
    fimZona=[random.randint(zona[0],5000),random.randint(zona[1],5000)]
    zona=[zona[0],zona[1],fimZona[0],fimZona[1]]
    img="https://picsum.photos/200/300"
    ling = stringGen(size=25)
    text2 = stringGen(size=55)
    tabelas["anomalia"].append((zona, img, ling, text2))

    final=insert.format("anomalia", "zona, imagem, lingua, descricao, tem_anomalia_traducao", "'("+str(zona[0])+","+str(zona[1])+","+str(zona[2])+","+str(zona[3])+")','"+img+"', '"+ling+"', '"+text2+"', 'False'")
    """if(random.randint(0,5)==0):3
        zona=[random.randint(5001,9998),random.randint(5001,9998)]
        fimZona=[random.randint(zona[0],9999),random.randint(zona[1],9999)]
        ling = stringGen(size=25)
        tabelas["anomalia_traducao"].append((zona, img, ling, text2))
        final.append(insert.format())
    """
    return final

def incidencia():
    global anomCutter
    anomalia = anomCutter
    anomCutter=anomCutter+1
    item = random.randint(1, len(tabelas["item"]))
    user = random.choice(tabelas["uq"])
    tabelas["incidencia"].append((anomalia, item, user))

    return insert.format("incidencia", "anomalia_id, item_id, email", "'"+str(anomalia)+"', '"+str(item)+"', '"+user+"'")


def stringGen(size=16, email=False):
    letters = string.ascii_letters
    text=''.join(random.choice(letters) for i in range(size))
    if email:
        return text+'@ist.utl.pt'
    return text

def coorGen():
    lon=random.random()*random.randint(-180,180)
    lat=random.random()*random.randint(-90,90)
    return (lat,lon)

def hardCodeMe():
    strings=[user(("samuel.barata@ist.utl.pt", "L0L")), user(("test@ist.utl.pt", "password"), 1), local(-90,-180,"min"), local(90,180,"max"), 
    "INSERT INTO anomalia (zona, imagem, lingua, descricao, tem_anomalia_traducao) VALUES('(0,0,50,50)','https://picsum.photos/200/300', 'Portugues', 'ta mal escrito', 'True');\n",
    "INSERT INTO anomalia_traducao(id, zona2, lingua2) VALUES ('1', '(0,50,50,100)', 'Ingles');\n",
    "INSERT INTO proposta_de_correcao (email, data_hora, texto) VALUES ('test@ist.utl.pt','2020-05-04 12:23:05' ,'text1');\n",
    "INSERT INTO proposta_de_correcao (email, data_hora, texto) VALUES ('test@ist.utl.pt','2020-05-04 12:23:05' ,'text2');\n"
    ]

    for i in strings:
        r.write(i)

r = open("projeto/p3/TESTES/samuel/populate.sql", 'w')
hardCodeMe()
for i in range(numUsers):
    r.write(user((stringGen(email=True),stringGen())))

for i in range(numLocais):
    r.write(local())

for i in range(numItens):
    r.write(item())

for i in range(numAnom):
    r.write(anomalia())

for i in range(numIncidencias):
    r.write(incidencia())

r.flush()
r.close()

conn = psycopg2.connect(host="localhost",database="proj3", user="postgres", password="")
cur = conn.cursor()

#r = open("projeto/p3/TESTES/samuel/populate.sql", 'r')
r = open("projeto/p3/populate.sql", 'r')

for i in r.readlines():
    #try:
    cur.execute(i)
    conn.commit()
#    except:
#        cur.close()
#        conn.close()
#        r.close()
#        print("TENS DE APAGAR OS DADOS TODOS DA BASE DE DADOS ANTES DE IMPORTAR NOVOS DADOS")
#        exit()
cur.close()
conn.close()
r.close()