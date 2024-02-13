USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_CargaCopiaEmpleados]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_CargaCopiaEmpleados]
AS
BEGIN
	
	WITH EmpleadosCTE AS
	(
	SELECT *, ROW_NUMBER() OVER(PARTITION by rut ORDER BY Rut) AS RowNumber
	From Tmp_carga
	)
	delete from EmpleadosCTE where RowNumber > 1


			MERGE SMU_DGT.[dbo].[empleados]  AS Target 
	USING 
	(  SELECT 
				LEFT( [Rut], LEN(RUT)-2) AS [empleadoid]
			  ,76012676 as empresaid
			  ,[nombre] + ' ' + ApPaterno + ' ' + ApMaterno as Nombre
			  ,[CodDivPersonal]      
			  ,[Rut]
			  ,RutEmpresa
		FROM [dbo].Tmp_Carga Emp

   ) 
		AS Source ON ( Target.[rutempleado] = Source.[empleadoid] --and Target.num_empresa = Source.empresaid     
	) 
	WHEN MATCHED THEN UPDATE SET  
		 Target.Nombre = Source.[nombre]
		,Target.[centro_costo]= Source.[CodDivPersonal]		
		,Target.Empresa = Source.RutEmpresa
	WHEN NOT MATCHED BY TARGET THEN 
		INSERT ( 
				[rutempleado]
				  ,[num_empresa]
				  ,[nombre]     
				  ,[centro_costo]    
				  ,[RutDV]
				  ,Empresa 
		)
		VALUES (
			  Source.[empleadoid]
			  ,Source.[empresaid]
			  ,Source.[nombre]
			  ,Source.[CodDivPersonal]      
			  ,Source.[Rut]
			  ,Source.RutEmpresa
		);


  MERGE [SMU_Gestor].[dbo].[personas]  AS Target 
	USING 
	(  
		SELECT distinct rut
			  ,[nombre]
			  ,Appaterno
			  ,ApMaterno
		  FROM [dbo].Tmp_Carga
		  group by rut, nombre,Appaterno,ApMaterno
		  having COUNT(Rut) = 1  
   ) 
		AS Source ON ( Target.[personaid] = Source.[Rut]   
	) 
	WHEN MATCHED THEN UPDATE SET  
		 Target.Nombre = Source.[nombre],
		 Target.appaterno = Source.Appaterno,
		 Target.apmaterno = Source.ApMaterno

	WHEN NOT MATCHED BY TARGET THEN 
		INSERT ( 
				[personaid]
				,[nombre]
				,appaterno
				,apmaterno
		)
		VALUES (
			  Source.[Rut]			  
			  ,Source.[nombre]	
			  ,Source.Appaterno	
			  ,Source.ApMaterno	

		);




	
		
 MERGE SMU_Gestor.[dbo].[empleados]  AS Target 
	USING 
	(   SELECT  
		[Rut]
		,[RutEmpresa] AS Empresa
      ,[CodDivPersonal]
      , CASE Rol WHEN 'Publico' THEN 0
				 WHEN 'General' THEN 0
				 WHEN 'Privado' THEN 1
		END as Rolid
		,CASE [Estado] WHEN 'Activos' THEN 0
				 WHEN 'Finiquitados' THEN 1
		END as estado
		FROM [dbo].Tmp_Carga Emp

   ) 
		AS Source ON ( Target.[empleadoid] = Source.[Rut]   --and target.empresaid = source.Empresa
	) 
	WHEN MATCHED THEN UPDATE SET  
		Target.[centrocostoid]= Source.[CodDivPersonal]	,
		Target.estado= Source.estado,	
		Target.Rolid= Source.Rolid,
		Target.Empresaid = Source.Empresa

	WHEN NOT MATCHED BY TARGET THEN 
		INSERT ( 
				[empleadoid]
				  ,[empresaid]
				  ,[centrocostoid]	  
				  ,[rolid]
				  ,estado
					)
		VALUES (
			  Source.[Rut]
			  ,Source.Empresa 
			  ,Source.[CodDivPersonal]  
			  ,Source.Rolid
			  ,Source.estado
		);				


END
GO
