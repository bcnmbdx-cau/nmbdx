import xlrd
import os
import traceback
import numpy as np

# 训练集与测试集划分
def Data_Segment(data_loc,seg,epoch,predict_time):
    # 训练集与测试集划分
    X_train = []
    y_train = []
    X_test = []
    y_test = []
    for data in data_loc:

        X_train_data = data[:int(seg * len(data))]

        for i in range(len(X_train_data) - epoch - predict_time):
            j = i + epoch
            X_train.append(data[i:j])
            y_train.append(data[j + predict_time])

        for i in range(len(X_train_data), len(data) - epoch - predict_time):
            j = i + epoch
            X_test.append(data[i:j])
            y_test.append(data[j + predict_time])

    return X_train,X_test,y_train,y_test

#读取数据表格
def Read_Data(FileName):
    try:
        work_book = xlrd.open_workbook(FileName)
        # 打开表格文件
        sheets_list = work_book.sheet_names()
        # 获取所有sheet
        sheets_num = len(sheets_list)
        Data = []
        # 溶解氧数据数组
        for i in range(sheets_num):
            # 读取每个sheet，对应一个水质数据点的时间序列
            # sheet_name = sheets_list[i]
            # print("已读取" + sheet_name)
            # 打印sheet名
            sheet = work_book.sheet_by_index(i)
            Data_point = []
            for j in range(sheet.nrows):
                # 读取每一行，对应一个时间
                rows = sheet.row_values(j)
                if (j != 0):
                    # 如果不是标题行，就导入数组
                    Data_point.append(rows[1])
            Data.append(np.array(list(map(lambda x: float(x),Data_point))))
        return Data

    except:
        traceback.print_exc()


def Get_Split_Data(Filename,seg=0.8,epoch=10,predict_time=1):
        Data=Read_Data(Filename)
        X_train, X_test, y_train, y_test=Data_Segment(Data,seg,epoch,predict_time)
        return X_train,X_test,y_train,y_test