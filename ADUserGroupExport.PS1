###############################################################
# Title: ActiveDirectory User Details Export. 
# Author: Arafat Hossain
# Version: 1.0
# Date: 2017-Jul-05
###############################################################
Import-Module ActiveDirectory
Get-ADUser -filter * -properties * | Export-csv -path C:\Users\<username>\UserAudit\AllUserList.csv -NoTypeInformation
Get-ADGroup -Filter * | Select Name | Export-csv -path C:\Users\<username>\UserAudit\Groups\Groups.csv -NoTypeInformation
$Groups = Import-CSV "C:\Users\<username>\UserAudit\Groups\Groups.csv"
FOREACH ($Group IN $Groups) {
	$Group =$Group.Name
    Get-ADGroupmember -Identity $Group  | select name, samaccountname, DistinguishedName | Export-csv -Path C:\Users\<username>\UserAudit\Users\$Group.csv -noTypeInformation
}
