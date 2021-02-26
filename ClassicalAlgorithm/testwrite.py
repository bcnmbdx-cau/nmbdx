import os
file = open('res.txt', 'w')
file.flush()
le=[1,2,3]
EV=["0","0","0"]
s=[le,EV]
for i in range(len(s)):
    for j in range(len(s[i])):
        file.write(str(s[i][j])+",")
    file.write("\r")
os.fsync(file)
file.close()