import xlrd
import time
def read():
    fname = "..\Algorithm\datas.xlsx"
    wb = xlrd.open_workbook(fname)
    try:
        sheet = wb.sheet_by_name("Sheet1")
    except:
        print("Error open" % fname)
    num_rows=sheet.nrows
    ttime = []
    DO = []
    electrolyze=[]
    pH=[]
    temperature=[]
    for i in range(1, num_rows):
        data = sheet.cell_value(i, 0)
        timeArray = time.strptime(data, "%Y-%m-%d %H:%M:%S")
        timeStamp = int(time.mktime(timeArray))-int(time.mktime(time.strptime("2018-1-1 00:00:00", "%Y-%m-%d %H:%M:%S")))
        ttime.append(timeStamp)
    for i in range(1, num_rows):
        data = float(sheet.cell_value(i, 1))
        DO.append(data)
    # for i in range(1, num_rows):
    #     data = float(sheet.cell_value(i, 2))
    #     electrolyze.append(data)
    for i in range(1, num_rows):
        data = float(sheet.cell_value(i, 3))
        pH.append(data)
    for i in range(1, num_rows):
        data = float(sheet.cell_value(i, 4))
        temperature.append(data)
    return ttime,DO,pH,temperature;