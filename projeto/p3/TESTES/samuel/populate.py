import random
import string

numUsers=0
numLocais=20
numItens=20
numAnom=20

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

def user(tup):
    tmp = random.randint(0,3)
    if tmp==0:
        text2 = "qualificado"
        tabelas["uq"].append(tup[0])
    else:
        text2 = "regular"
        tabelas["ur"].append(tup[0])

    tabelas["utilizador"].append(tup)
    text = insert.format("utilizador", "email, password", "'"+tup[0] + "', '"+tup[1]+"'") + insert.format("utilizador_{}".format(text2), "email", "'"+tup[0]+"'")
    return text

def local():
    text = stringGen()
    tmp=coorGen()
    lat=float("{:.6f}".format(tmp[0]))
    lon=float("{:.6f}".format(tmp[1]))
    tabelas["local_publico"].append((lat,lon,text))
    return insert.format("local_publico", "latitude, longitude, nome", "'"+str(lat)+"', '"+str(lon)+"', '"+text+"'")

def item():
    text = stringGen(size=200)
    text2 = stringGen()
    a=random.choice(tabelas["local_publico"])
    lat=a[0]
    lon=a[1]
    tabelas["item"].append((text, text2, lat, lon))
    return insert.format("item", "descricao, localizacao, latitude, longitude", "'"+text+"', '"+text2+"', '"+str(lat)+"', '"+str(lon)+"'")

def anomalia():
    zona=[random.randint(0,9998),random.randint(0,9998)]
    fimZona=[random.randint(zona[0],9999),random.randint(zona[1],9999)]
    zona=[zona[0],zona[1],fimZona[0],fimZona[1]]
    img="https://picsum.photos/200/300"
    ling = stringGen(size=25)
    text2 = stringGen(size=55)
    tabelas["anomalia"].append((zona, img, ling, text2))
    return insert.format("anomalia", "zona, imagem, lingua, descricao", "'("+str(zona[0])+","+str(zona[1])+","+str(zona[2])+","+str(zona[3])+")','"+img+"', '"+ling+"', '"+text2+"'")


def stringGen(size=16, email=False):
    letters = string.ascii_letters
    text=''.join(random.choice(letters) for i in range(size))
    if email:
        text = text.join('@ist.utl.pt')
    return text

def coorGen():
    lon=random.random()*random.randint(-180,180)
    lat=random.random()*random.randint(-90,90)
    return (lat,lon)

def hardCodeMe():
    strings=[user(("samuel.barata@ist.utl.pt", "L0L")),    ]
    for i in strings:
        r.write(i)

r = open("projeto/p3/TESTES/samuel/populate.sql", 'w')
for i in range(numUsers):
    r.write(user((stringGen(email=True),stringGen())))

for i in range(numLocais):
    r.write(local())

for i in range(numItens):
    r.write(item())

for i in range(numAnom):
    r.write(anomalia())

#hardCodeMe()
r.flush()
r.close()