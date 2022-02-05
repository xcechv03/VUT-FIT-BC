#include <iostream>
#include <fstream>
#include <string>
#include <string.h>
#include <algorithm>
#include <cmath>
#include <vector>
using namespace std;

/** Php like function to split string into array based on character inside string */
vector<string> explode(string s, char c){
    int n = s.length();
    int split_count = 0;
    vector<string> output(count(s.begin(),s.end(),c)+1);
    for (int i = 0; i < n; ++i) {
        if(s[i] == c){
            split_count++;
            continue;
        }
        else{
            output[split_count] += s[i];
        }
    }
    return output;
}

int main(int argc, char* argv[]) {
    if (argc != 2){
        cout << "Invalid arguments!" << endl;
        return -1;
    }
    if (strcmp(argv[1],"help") == 0){
        cout << "\nStartup: './main n' where 'n' is number of observed periods" << endl;
        cout << "Provide starting and ending year quartile(1-4) and value for calculation eg.:" << endl;
        cout << "start:" << endl;
        cout << "2020 1 1786" << endl;
        cout << "end:" << endl;
        cout << "2021 1 1456" << endl;
        return 1;
    }
    int n = stoi(argv[1]);
    int i = 0;
    vector<string> result(n);
    while (i < n) {
        cout << "start:" << endl;
        string start;
        getline(cin, start);

        cout << "end:" << endl;
        string end;
        getline(cin, end);

        vector<string> start_arr = explode(start,' ');
        vector<string> end_arr = explode(end,' ');
        if (start_arr.size() != 3 || end_arr.size() != 3){
            cout << start_arr.size() << " " << end_arr.size() << endl;
            cerr << "Invalid format! run ./main help for manual" << endl;
            continue;
        }

        int s = stoi(start_arr[2]);
        int e = stoi(end_arr[2]);
        double res = (double ) (e-s)/s;
        res = res*100;
        string res_string = to_string(round(res*100.0)/100.0);
        res_string.pop_back();
        res_string.pop_back();
        res_string.pop_back();
        res_string.pop_back();
        result[i] = ""+start_arr[0] +" " + start_arr[1]+"Q"+ " / "+ end_arr[0]+ " " + end_arr[1]+"Q" + " = " + res_string +"%";
        i++;
    }
    ofstream f;
    f.open("data.csv");
    for (int j = 0; j < n; j++) {
        cout << result[j] << endl;
        f << result[j] << endl;
        f << "semi;colon";
    }
    f.close();
    return 0;
}
