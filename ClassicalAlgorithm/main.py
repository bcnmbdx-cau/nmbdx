import os
import joblib
import numpy as np
from sklearn.svm import SVR
from matplotlib import pyplot as plt
from sklearn.neural_network import MLPRegressor
from sklearn.ensemble import GradientBoostingRegressor
from sklearn.model_selection import TimeSeriesSplit,GridSearchCV
from sklearn.metrics import explained_variance_score, mean_absolute_error, mean_squared_error, r2_score
from ClassicalAlgorithm.Data_Processor import Get_Split_Data
import warnings

class Test:
    def __init__(self,model,X_test,y_test,name):
        self.model=model
        self.name = name
        self.X_test=X_test
        self.y_test=y_test
        self.y_test_predict = []

    # 采用EV,MAE,MSE,R2进行结果评估
    def Model_Metrics(self):
        print(self.name+" metrics")
        metrics_functions_name=['EV','MAE','MSE','R2']
        metrics_functions = [explained_variance_score, mean_absolute_error, mean_squared_error, r2_score]
        metrics_res=[]
        for i in range(4):
            print(metrics_functions_name[i],end=":")
            print(round(metrics_functions[i](self.y_test, self.y_test_predict),6), end=" ")
            metrics_res.append(metrics_functions[i](self.y_test, self.y_test_predict))
        print("\n")
        return metrics_res

    # 画图
    def Draw(self):
        plt.plot(self.y_test, "-", linewidth=1)
        plt.plot(self.y_test_predict, "-", linewidth=1)
        plt.title(self.name)
        plt.show()
        #plt.savefig("Prediction_Pictures/"+self.name+".png")
        #plt.close()

    # 测试模型
    def Model_Test(self):
        self.y_test_predict = self.model.predict(self.X_test)
        metrics_res=self.Model_Metrics()
        self.Draw()
        return metrics_res

class Train:
    def __init__(self,model_object,X_train,y_train,time_length):
        #训练参数
        self.model_object = model_object
        self.name=model_object.name
        self.X_train = X_train[:]
        self.y_train = y_train[:]
        self.time_length=time_length

    # 交叉验证+网格搜索找到最优模型
    def Model_Train(self):
        #指定网格搜索参数,找到最优模型
        tscv = TimeSeriesSplit(max_train_size=None, n_splits=5).split(self.X_train)
        cvmodel=GridSearchCV(self.model_object.model,self.model_object.params,refit=True,return_train_score=True,cv=tscv,n_jobs=-1)
        cvmodel.fit(np.array(self.X_train),np.array(self.y_train))
        print(self.name+"得分:"+str(cvmodel.best_score_))
        print(cvmodel.best_estimator_)
        joblib.dump(cvmodel.best_estimator_, filename="model/" + self.name + "_"+str(self.time_length)+"0min.pkl")
        return cvmodel.best_estimator_

class Support_Vactor_Regression:
    def __init__(self):
        self.model = SVR()
        # 交叉验证参数列表
        self.params = [{
            'kernel': ['linear','rbf'],
            'C': [0.01],
            'gamma': [100, 10, 1, 0.1, 0.01, 0.001],
            'max_iter' : [40000]
        }]
        self.name="SVR"

class Multilayer_Perceptron_Regressor:
    def __init__(self):
        self.model = MLPRegressor()
        #交叉验证参数列表
        self.params = [
            {
                'hidden_layer_sizes': [(7, 7)],
                'max_iter' : [20000],
                'tol': [1e-2, 1e-3, 1e-4, 1e-5, 1e-6],
                'epsilon': [1e-3, 1e-7, 1e-8, 1e-9, 1e-8]
             }
        ]
        self.name="MLPRegressor"

class Gradient_Boosting_Regressor:
    def __init__(self):
        self.model=GradientBoostingRegressor()
        # 交叉验证参数列表
        self.params = []
        self.name="GBRegressor"


#生成预测模型
def Get_Model(Filename,epoch,time_length):
    X_train, X_test, y_train, y_test=Get_Split_Data(Filename,epoch=epoch,predict_time=time_length)
    model_object_dir = [ Support_Vactor_Regression()]
    for model_object in model_object_dir:
        Do_Train=Train(model_object, X_train, y_train,time_length)
        Model_Aft_Train=Do_Train.Model_Train()
        Do_Test=Test(Model_Aft_Train,X_test,y_test,model_object.name)
        metrics_res=Do_Test.Model_Test()


#模型泛化测试
def Model_Generalization_Test(Test_Data_Address, Model_Address,epoch,time_length):
    models={}
    Test_Data_dir = os.listdir(Test_Data_Address)
    Model_dir=os.listdir(Model_Address)
    for Model in Model_dir:
        Filename=Model_Address+'/'+Model
        print(Filename)
        models[str(Model)[:-4]]=(joblib.load(Filename))
    for Test_Data in Test_Data_dir:
            Filename = Test_Data_Address + '/' + Test_Data
            Test_Data=Test_Data[:-4]
            X_test, X_none, y_test, y_none = Get_Split_Data(Filename,seg=1,epoch=epoch,predict_time=time_length)
            print(Test_Data)
            for name,model in models.items():
                Do_Test = Test(model, X_test, y_test,name+" "+str(Test_Data))
                Do_Test.Model_Test()


if __name__ == '__main__':
    warnings.filterwarnings("ignore")
    Train_Data_Name='Data.xls'
    Test_Data_Address = './Test_Data'
    Model_Address = './model'
    for time_length in range(1,7):
        metrics_res = Get_Model(Train_Data_Name, 15,time_length)
    # EV=[]
    # MAE=[]
    # MSE=[]
    # R2=[]
    # length=[]
    # for epoch in range(8,15):
    #     metrics_res=Get_Model(Train_Data_Name,epoch)
    #     EV.append(metrics_res[0])
    #     MAE.append(metrics_res[1])
    #     MSE.append(metrics_res[2])
    #     R2.append(metrics_res[3])
    #     length.append(epoch)
        #Model_Generalization_Test(Test_Data_Address,Model_Address,epoch)
    # plt.plot(len,EV)
    # plt.xlabel('Input Time Length')
    # plt.title("EV")
    # plt.show()
    # plt.plot(len, MAE)
    # plt.xlabel('Input Time Length')
    # plt.title("MAE")
    # plt.show()
    # plt.plot(len, MSE)
    # plt.xlabel('Input Time Length')
    # plt.title("MSE")
    # plt.show()
    # plt.plot(len, R2)
    # plt.xlabel('Input Time Length')
    # plt.title("R2")
    # plt.show()
    # s=[length,EV,MAE,MSE,R2]
    # file = open('res.txt', 'w')
    # file.flush()
    # for i in range(len(s)):
    #     for j in range(len(s[i])):
    #         file.write(str(s[i][j]) + ",")
    #     file.write("\r")
    # os.fsync(file)
    # file.close()