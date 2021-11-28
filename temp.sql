SELECT 
	*,
    count(at.idassettype) - 1
FROM `assettype` as at
	left outer join asset as a on (a.idassettype = at.idassettype)
    left outer join rent as r on (r.idasset = a.idasset)
    left outer join tenant as t on (t.idtenant = r.idtenant)
WHERE 1
Group by t.idtenant, at.idassettype


SELECT 
	MAX(ss.rentcount) AS rentcount,
    ss.rerentmax - MAX(ss.rentcount) AS openrents,
    group_concat(ss.Mail) AS mail,
    ss.Name,
    ss.idassettype
FROM (
    SELECT 
        IF (t.idtenant IS NULL, 0, COUNT(at.idassettype)) AS rentcount,
        t.Mail,
    	t.idtenant,
        at.Name,
    	at.rerentmax,
    	at.idassettype
    FROM 
        `assettype` AS at
        LEFT JOIN asset AS a ON (a.idassettype = at.idassettype)
        LEFT JOIN rent AS r ON (r.idasset = a.idasset)
        LEFT JOIN tenant AS t ON (t.idtenant = r.idtenant AND t.idtenant = 9)
    GROUP BY t.idtenant, at.idassettype
    ) as ss
    JOIN assettype AS at ON (ss.idassettype = at.idassettype)
    GROUP BY at.idassettype


    