from matplotlib import pyplot as plt
import sys
import numpy as np
from sklearn.svm import SVR
from sklearn import metrics
from read_data import read
read_arr=np.array(read())
ll=[]
for i in range(1,4):
    ll.append(sys.argv[i].split(","))
arr_1=np.array(list(map(float,ll[0])))
arr1=[]
arr1.append(arr_1[0:10])
arr_2=np.array(list(map(float,ll[1])))
arr2=[]
arr2.append(arr_2[0:10])
arr_3=np.array(list(map(float,ll[2])))
arr3=[]
arr3.append(arr_3[0:10])
prediction=3   #预测半个小时后的数据
def svr_train(c_set,gamma_set,data):
    num_data=read_arr[0][:int(0.8 * len(read_arr[0]))]
    num_X=10
    svr = SVR(kernel='rbf', C=c_set, gamma=gamma_set)
    X_train_input = []
    X_train_output = []
    X_test_input = []
    X_test_true = []
    for i in range(len(num_data) - num_X - 1):
        j = i + num_X
        X_train_input.append(data[i:j])
        X_train_output.append(data[j + 1])
    for i in range(len(num_data),len(read_arr[0]) - num_X - 1):
        j = i + num_X
        X_test_input.append(data[i:j])
        X_test_true.append(data[j + 1])
    Fx=svr.fit(X_train_input[:-num_X],X_train_output[:-num_X])
    return X_test_true,Fx.predict(X_test_input[:]),Fx
F=[]
x = range(0,11,1)
for i in range(1,4):
    X_test_true,X_test_res,Fx=svr_train(10,0.01,read_arr[i])
    F.append(Fx)
    # print(metrics.mean_squared_error(X_test_true, X_test_res))
    # print(metrics.mean_absolute_error(X_test_true, X_test_res))
array=F[0].predict(arr1[:])
print(array[0])
plt.plot(list(map(float,ll[0])),"-",linewidth=1,marker="o")
plt.plot(10,array[0],"-",linewidth=1,marker="o",linestyle='dashed')
plt.xticks(x)
plt.ylabel("DO")
plt.title("The forecast of DO")
plt.savefig("../upload/DO.png")
plt.close()
array=F[1].predict(arr2[:])
print(array[0])
plt.plot(list(map(float,ll[1])),"-",linewidth=1,marker="o")
plt.plot(10,array[0],"-",linewidth=1,marker="o",linestyle='dashed')
plt.xticks(x)
plt.ylabel("pH")
plt.title("The forecast of pH")
plt.savefig("../upload/pH.png")
plt.close()
array=F[2].predict(arr3[:])
print(array[0])
plt.plot(list(map(float,ll[2])),"-",linewidth=1,marker="o")
plt.plot(10,array[0],"-",linewidth=1,marker="o",linestyle='dashed')
plt.xticks(x)
plt.ylabel("Temperature")
plt.title("The forecast of Temperature")
plt.savefig("../upload/temperature.png")
plt.close()